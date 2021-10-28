$(document).ready(function(){
    const controller = "/competition/player/ajax/";
    let competitionId = $("#competitionId").val();

    sendajax(competitionId, controller)

    setInterval(sendajax, 5000, competitionId, controller);
});

function sendajax(competitionId, controller){
    let data = new FormData();
    data.append("competitionId", competitionId);

    $.ajax({
        url: controller + competitionId,
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
    let buildDiv = $("#buildHtml").empty();

    for(let i = 0; i < response.length; i++){
        let html = '<div class="card w-75  text-center mt-3">' +
                '<div class="card-body">' +
                    '<h5 class="card-title">' +
                        '<p>Играет ' + response[i]["firstPlayer"] + ' против ' + response[i]["secondPlayer"] + '</p>' +
                    '</h5>' +
                '</div>' +
            '</div>';

        $(buildDiv).append(html);
    }
}