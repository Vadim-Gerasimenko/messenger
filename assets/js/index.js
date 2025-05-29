$(document).ready(function () {
    const searchFormSubmitButton = $("#search-form-submit-button");
    const dialogsList = $("#pen-pals-list");
    const noDialogsMessage = $("#no-dialogs-message");

    const urlParams = new URLSearchParams(window.location.search);
    const pattern = urlParams.get("search") ?? "";

    function getDialogs(pattern = "") {
        pattern = pattern.trim().toLowerCase();
        
        $.get("actions/getting_dialogs.php", {pattern: pattern}, function (res) {
            let response = JSON.parse(res);

            if (response.success === false) {
                return;
            }

            dialogsList.empty();

            if (response.data.length === 0) {
                $(noDialogsMessage).removeClass("d-none");
                return;
            }

            $(noDialogsMessage).addClass("d-none");

            response.data.forEach(dialog => {
                let avatar = dialog.avatar_path === null
                    ? "service/uploads/images/avatars/default_avatar.jpg"
                    : dialog.avatar_path;

                let messageBlock = $("<div>");

                if (dialog.last_message_sender_id !== dialog.user_id) {
                    messageBlock.append($("<span>").addClass("text-secondary").text("Вы: "));
                }

                let lastMessage = (dialog.last_message_text).trim();

                if (lastMessage.length === 0) {
                    messageBlock.append($("<span>").addClass("text-primary").text("Вложение"));
                } else {
                    messageBlock.append($("<span>").text(lastMessage));
                }

                let dialogsListItem = $("<li>")
                    .addClass("list-group-item list-group-item-action d-flex justify-content-between align-items-start")
                    .append($("<div>")
                        .addClass("me-auto d-inline-flex")
                        .append($("<div>").addClass("me-2")
                            .append($("<img>").addClass("rounded-circle")
                                .attr("width", "50px")
                                .attr("height", "50px")
                                .attr("alt", "avatar")
                                .attr("src", `/assets/data/${avatar}`)
                            )
                        )
                        .append($("<div>").addClass("d-inline-block")
                            .append($("<div>").addClass("fw-semibold")
                                .append($("<span>").addClass("me-1").text(dialog.name))
                                .append($("<span>").text(dialog.surname))
                            )
                            .append(messageBlock)
                        )
                    );

                if (dialog.unread_messages_number > 0) {
                    dialogsListItem.append($("<span>")
                        .addClass("badge bg-primary rounded-pill").text(dialog.unread_messages_number));
                }

                $(dialogsListItem).click(function () {
                    location.href = `dialog.php?id=${dialog.dialog_id}`;
                });

                dialogsList.append(dialogsListItem);
            });
        });
    }

    getDialogs(pattern);

    setInterval(function () {
        getDialogs(pattern);
    }, 3000);

    $(searchFormSubmitButton).submit(function (e) {
        e.preventDefault();
    });
});