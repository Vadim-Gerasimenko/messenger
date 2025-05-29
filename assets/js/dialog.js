$(document).ready(function () {
    const backToIndexButton = $("#back-to-index-button");
    const userInfoBlock = $("#user-info");
    const onlineStatus = $("#online-status");
    const dialogContent = $("#dialog-content");
    const inputMessageForm = $("#input-msg-form");
    const inputMessageText = $("#input-msg-text");
    const noMessagesInfoBlock = $(dialogContent).find(".no-messages-info");

    let lastMessageId = null;
    let savedMessages = [];
    let dialogInfo = null;
    let penPalId = null;
    let previousDate = null;
    let lastMessage = null;
    let isScrolled = false;

    let filesType = null;
    let data = new FormData();

    let params = new URLSearchParams(document.location.search);
    let dialogId = params.get("id");

    getDialog(dialogId);
    setInterval(getDialog, 2000, dialogId);

    setInterval(updateOnlineStatus, 300000, penPalId);

    $(backToIndexButton).click(function () {
        redirect();
    });

    $(inputMessageText).on("keydown", function (e) {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            $(inputMessageForm).submit();
        }
    });

    $(inputMessageForm).on("submit", function (e) {
        e.preventDefault();

        const message = $(inputMessageText).val().trim();

        if (!message || dialogInfo === null) {
            return;
        }

        const type_id = 1;

        sendMessage({
            dialog_id: dialogId,
            receiver_id: dialogInfo.user_id,
            type_id: type_id,
            text: message
        });

        $(inputMessageText).val("");
    });

    function redirect(address = "index.php") {
        location.href = address;
    }

    function getDialog(id) {
        $.post("actions/reading_messages.php", {dialog_id: id}, function (res) {
            let response = JSON.parse(res);

            if (response.success === false) {
                redirect();
            }
        });

        $.get("actions/getting_messages.php", {dialog_id: id, message_id: lastMessageId}, function (res) {
            let response = JSON.parse(res);

            if (response.success === false) {
                redirect();
            }

            if (dialogInfo === null) {
                dialogInfo = response.dialog_info;
                penPalId = dialogInfo.user_id;

                updateOnlineStatus(penPalId);

                let avatar = dialogInfo.avatar_path === null
                    ? "service/uploads/images/avatars/default_avatar.jpg"
                    : dialogInfo.avatar_path;

                $(userInfoBlock).find(".avatar").attr("src", `/assets/data/${avatar}`);
                $(userInfoBlock).find(".name").text(dialogInfo.name);
                $(userInfoBlock).find(".surname").text(dialogInfo.surname);
            }

            let messages = response.messages;

            if (savedMessages.length === 0 && messages.length === 0) {
                $(noMessagesInfoBlock).removeClass("d-none");
                return;
            }

            savedMessages.push(...messages);

            if (savedMessages.length !== 0) {
                lastMessageId = savedMessages[savedMessages.length - 1].id;
            }

            messages.forEach(message => {
                let sent_date = new Date(message.sent_date);

                if (isNewDay(previousDate, sent_date)) {
                    $(dialogContent)
                        .append($("<div>").addClass("d-flex justify-content-center text-secondary mb-4 mt-4")
                            .text(`${formatDate(sent_date)}`)
                        );

                    previousDate = sent_date;
                }

                let messageWrapper = $("<div>").addClass("d-flex mb-2");
                let messageItem = $("<div>").addClass("card message px-3 pt-2 pb-1");

                const timeString = sent_date.toLocaleTimeString([], {hour: '2-digit', minute: '2-digit'});
                let timeBlock = $("<div>").addClass("time d-flex").text(timeString);

                if (message.sender_id === dialogInfo.user_id) {
                    messageWrapper.addClass("justify-content-start");
                    messageItem.addClass("bg-primary text-white");
                    timeBlock.addClass("justify-content-start")
                } else {
                    messageWrapper.addClass("justify-content-end");
                    messageItem.addClass("bg-light");
                    timeBlock.addClass("justify-content-end text-secondary")
                }

                switch (message.type_name) {
                    case "image":
                        message.files.forEach(file => {
                            $(messageItem).append($("<div>").addClass("my-2")
                                .append($("<img>")
                                    .addClass("sent-media img-fluid rounded")
                                    .attr("src", `/assets/data/${file.path}`)
                                    .attr("alt", "Изображение")
                                )
                            );
                        });
                        break;
                    case "video":
                        message.files.forEach(file => {
                            $(messageItem).append($("<div>").addClass("my-2")
                                .append($("<video>")
                                    .addClass("sent-media w-100 rounded")
                                    .attr("controls", "")
                                    .append($("<source>")
                                        .attr("src", `/assets/data/${file.path}`)
                                        .attr("alt", "Видео")
                                        .text("Формат видео не поддерживается.")
                                    )
                                )
                            );
                        });
                        break;
                    case "file":
                        message.files.forEach(file => {
                            $(messageItem).append(createFileAttachment(file.name, file.size, file.path));
                        });
                        break;
                }

                $(messageItem).append($("<div>").text(message.text));
                $(messageItem).append(timeBlock);
                $(messageWrapper).append(messageItem);
                $(dialogContent).append(messageWrapper);

                lastMessage = $(messageWrapper);
            });

            if (!isScrolled) {
                dialogContent.scrollTop(5 * $(lastMessage).offset().top);
                isScrolled = true;
            }
        });
    }

    function updateOnlineStatus(userId) {
        if (userId === null) {
            return;
        }

        $.get("actions/getting_last_seen.php", {user_id: userId}, function (res) {
            let response = JSON.parse(res);

            if (response.success === false) {
                return;
            }

            $(onlineStatus).text(getOnlineStatus(response.last_seen_date));
        });
    }

    function isNewDay(previousDate, newDate) {
        if (previousDate === null) {
            return true;
        }

        return (
            previousDate.getDate() !== newDate.getDate() ||
            previousDate.getMonth() !== newDate.getMonth() ||
            previousDate.getFullYear() !== newDate.getFullYear()
        );
    }

    function getDate() {
        let date = new Date();
        return new Date(date - date.getTimezoneOffset() * 60000).toISOString().split('T').join(' ').slice(0, 19);
    }

    const monthNames = [
        'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
        'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
    ];

    function formatDate(dateString) {
        const now = new Date();
        const inputDate = new Date(dateString);

        const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
        const inputDay = new Date(inputDate.getFullYear(), inputDate.getMonth(), inputDate.getDate());
        const diffDays = Math.floor((today - inputDay) / (1000 * 60 * 60 * 24));

        const day = inputDate.getDate();
        const month = monthNames[inputDate.getMonth()];
        const year = inputDate.getFullYear();

        if (diffDays === 0) {
            return "Сегодня";
        } else if (diffDays === 1) {
            return "Вчера";
        } else if (diffDays === 2) {
            return "Позавчера";
        } else if (inputDate.getFullYear() === now.getFullYear()) {
            return `${day} ${month}`;
        } else {
            return `${day} ${month} ${year}`;
        }
    }

    function getOnlineStatus(timeString) {
        const now = new Date();
        const inputDate = new Date(timeString);

        if (inputDate >= now) {
            return "online";
        }

        const timeStr = inputDate.toLocaleTimeString('ru-RU', {
            hour: '2-digit',
            minute: '2-digit'
        });

        const isToday = inputDate.toDateString() === now.toDateString();
        const isYesterday = new Date(now - 86400000).toDateString() === inputDate.toDateString();

        if (isToday) {
            return `был(-а) сегодня в ${timeStr}`;
        }

        if (isYesterday) {
            return `был(-а) вчера в ${timeStr}`;
        }

        const day = inputDate.getDate();
        const month = monthNames[inputDate.getMonth()];

        return `был(-а) ${day} ${month} в ${timeStr}`;
    }

    function createFileAttachment(fileName, fileSize, filePath) {
        const fileIcons = {
            "pdf": "bi-file-earmark-pdf-fill",
            "doc": "bi-file-earmark-word-fill",
            "docx": "bi-file-earmark-word-fill",
            "xls": "bi-file-earmark-excel-fill",
            "xlsx": "bi-file-earmark-excel-fill",
            "ppt": "bi-file-earmark-ppt-fill",
            "pptx": "bi-file-earmark-ppt-fill",
            "zip": "bi-file-earmark-zip-fill",
            "rar": "bi-file-earmark-zip-fill",
            "jpg": "bi-file-earmark-image-fill",
            "png": "bi-file-earmark-image-fill",
            "mp3": "bi-file-earmark-music-fill",
            "mp4": "bi-file-earmark-play-fill",
            "default": "bi-file-earmark-text-fill"
        };

        const fileExt = fileName.split(".").pop().toLowerCase();
        const iconClass = fileIcons[fileExt] || fileIcons["default"];

        const fileAttachment = $("<div>").addClass("file-attachment bg-light rounded p-3 mb-2");

        const fileContent = $("<div>").addClass("d-flex align-items-center").appendTo(fileAttachment);

        $("<div>")
            .addClass("file-icon bg-primary rounded p-2 me-3")
            .append(
                $("<i>").addClass(`bi ${iconClass} text-white fs-4`)
            )
            .appendTo(fileContent);

        const fileInfo = $("<div>").addClass("file-info").appendTo(fileContent);
        $("<div>").addClass("fw-bold text-black").text(fileName).appendTo(fileInfo);
        $("<div>").addClass("text-muted small").text(formatFileSize(fileSize)).appendTo(fileInfo);

        $("<div>")
            .addClass("ms-auto")
            .append(
                $("<a>")
                    .addClass("btn btn-sm btn-link text-primary")
                    .attr("title", "Скачать")
                    .attr("href", `/assets/data/${filePath}`)
                    .attr("download", fileName)
                    .attr("target", "_blank")
                    .append(
                        $("<i>").addClass("bi bi-download")
                    )
            )
            .appendTo(fileContent);

        return fileAttachment;
    }

    function sendMessage(requestBody) {
        $.post("actions/sending_message.php", requestBody, function (res) {
            let response = JSON.parse(res);

            if (response.success === false) {
                redirect();
                return;
            }

            getDialog(dialogId);
        });
    }

    const fileInput = $("#file-input");
    const imageButton = $("#image-btn");
    const videoButton = $("#video-btn");
    const fileButton = $("#file-btn");

    const filesSendingModal = new bootstrap.Modal($("#files-sending-modal"));
    const modalBody = $("#files-sending-modal .modal-body");

    const inputMessageWithFilesText = $("#input-msg-with-files-text");
    const inputFilesForm = $("#input-files-form");

    $(filesSendingModal).on("show.bs.modal", function () {
        $(inputMessageWithFilesText).val($(inputMessageText).val());
    });

    $(filesSendingModal).on("hidden.bs.modal", function () {
        $(inputMessageText).val($(inputMessageWithFilesText).val());
    });

    $(imageButton).click(function (e) {
        e.preventDefault();

        filesType = 2;
        $(fileInput).attr("accept", "image/*").click();
    });

    $(videoButton).click(function (e) {
        e.preventDefault();

        filesType = 3;
        $(fileInput).attr("accept", "video/*").click();
    });

    $(fileButton).click(function (e) {
        e.preventDefault();

        filesType = 4;
        $(fileInput).removeAttr("accept").click();
    });

    $(fileInput).change(function () {
        if (this.files && this.files.length > 0) {
            $(inputMessageWithFilesText).val($(inputMessageText).val());
            modalBody.empty();

            const keys = [];

            Array.from(this.files).forEach((file) => {
                let key = file.name + generateRandomString();

                data.append(key, file);
                keys.push(key);

                const fileType = file.type.split('/')[0];
                const fileName = file.name;
                const fileSize = file.size;
                const objectUrl = URL.createObjectURL(file);

                switch (fileType) {
                    case "image":
                        modalBody.append(
                            `<div class="my-2">
                            <img class="sent-media img-fluid rounded" src="${objectUrl}" alt="${fileName}">
                         </div>`
                        );
                        break;
                    case "video":
                        modalBody.append(
                            `<div class="mb-2">
                            <video class="sent-media mt-2 w-100 rounded" controls>
                                <source src="${objectUrl}" type="${file.type}">
                                Формат видео не поддерживается.
                            </video>
                        </div>`
                        );
                        break;
                    default:
                        modalBody.append(createFileAttachment(fileName, fileSize));
                }
            });

            data.append("file_keys", keys);
            filesSendingModal.show();
        }
    });

    $(inputMessageWithFilesText).on("keydown", function (e) {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            $(inputFilesForm).submit();
        }
    });

    $(inputFilesForm).on("submit", function (e) {
        e.preventDefault();

        if (dialogInfo === null) {
            return;
        }

        const typeId = filesType;
        const message = $(inputMessageWithFilesText).val().trim();

        data.append("text", message);
        data.append("dialog_id", dialogId);
        data.append("receiver_id", dialogInfo.user_id);
        data.append("type_id", typeId);

        $.ajax({
            url: "actions/sending_message.php",
            type: "POST",
            data: data,
            processData: false,
            contentType: false,
            success: function(response) {
                let res = JSON.parse(response);

                $(inputMessageWithFilesText).val("");
                filesSendingModal.hide();

                getDialog(dialogId);
            },
            error: function () {
                $(inputMessageWithFilesText).val("");
                filesSendingModal.hide();

                redirect();
            }
        });
    });

    function formatFileSize(bytes) {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
    }

    function generateRandomString(length = 5) {
        const chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let result = '';

        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        return result;
    }
});