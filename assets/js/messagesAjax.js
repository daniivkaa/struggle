$(document).ready(function(){
    const controller = "/user/comments/ajax/";
    let targetUserId = $("#targetUserId").val();
    let secondUserId = $("#secondUserId").val();
    let messageId = $("#messageId").val();

    sendajax(targetUserId, secondUserId, messageId, controller)

    setInterval(sendajax, 3000, targetUserId, secondUserId, messageId, controller);
});

function sendajax(targetUserId, secondUserId, messageId, controller){
    let data = new FormData();
    data.append("targetUserId", targetUserId);
    data.append("secondUserId", secondUserId);
    data.append("messageId", messageId);

    $.ajax({
        url: controller + targetUserId + "/" + secondUserId + "/" + messageId,
        type: "POST",
        dataType: "json",
        contentType: false,
        cache: false,
        processData: false,
        data: data,
        success:
            function(response){
                buildHtml(response);
            },
        error:
            function(){
                console.log("error")
            }
    });
}

function buildHtml(response){
    let buildDiv = $("#messages_ajax").empty();

    for(let i = 0; i < response.length; i++){
        let html = '<p>' + response[i]["firstName"] + ' написал: ' + response[i]["content"] + '</p>';

        $(buildDiv).append(html);
    }
}