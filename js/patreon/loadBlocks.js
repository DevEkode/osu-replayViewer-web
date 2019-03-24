//Ajax function to get json
$(document).ready(function(){
    $.getJSON( "php/patreon/getPledgers.php", function( data ) {
        data.forEach(function(element) {
            insertBlock(element);
        });
    });
});

function insertBlock(array){

    var tier_name = array.title;
    var pledgers_nb = array.patron_count;
    var tier_price = array.amount_cents/100;
    var join_url = 'https://www.patreon.com'+array.url;
    var pledgers_array = array.pledgers;

    var url = new URL(join_url);
    var join_id = url.searchParams.get("rid");

    //Construct pledgers list
    var pledgers = "";
    pledgers_array.forEach(function(element) {
        if(element !== ""){
            pledgers += element+',';
        }
    });


    var result = "<div class=\"container-fluid\" id=\"tier_block\">\n" +
        "        <div class=\"row\">\n" +
        "            <div class=\"col-xs-9 col-sm-9 col-md-9\">\n" +
        "                <h3><b>"+tier_name+"</b>\n" +
        "                <small class=\"text-muted\">- "+pledgers_nb+" pledgers</small>\n" +
        "                </h3>\n" +
        "            </div>\n" +
        "            <div class=\"col-xs-3 col-sm-3 col-md-3\" id=\"join_block_button\">\n" +
        "               <form action="+join_url+" target=\"_blank\">\n" +
        "                <button type=\"submit\" class=\"btn btn-danger\" >Join for "+tier_price+"$ !</button>\n" +
        "                <input type=\"hidden\" name=\"rid\" value=\""+join_id+"\"/>\n" +
        "               </form>\n" +
        "            </div>\n" +
        "        </div>\n" +
        "        <div class=\"row\">\n" +
        "            </p>\n" +
        "            <div class=\"col-xs-9 col-sm-9 col-md-9\">";

        var next = "";
        if(pledgers !== ""){
            next =
            "                <h4>Thank you ❤️,<h4>\n" +
            "                <p class=\"lead\">\n" +
            "                <small class=\"text-muted\">"+pledgers+"</small>\n" +
            "            </div>\n" +
            "            <div class=\"col-xs-3 col-sm-3 col-md-3\">\n" +
            "                <small class=\"text-muted\">View all the benefits on the patreon page</small>\n" +
            "            </div>\n" +
            "        </div>\n" +
            "    </div>";
        }else{
            next =
                "                <p class=\"lead\">\n" +
                "                <small class=\"text-muted\">Be the first one to pledge this tier !</small>\n" +
                "            </div>\n" +
                "            <div class=\"col-xs-3 col-sm-3 col-md-3\">\n" +
                "                <small class=\"text-muted\">View all the benefits on the patreon page</small>\n" +
                "            </div>\n" +
                "        </div>\n" +
                "    </div>";
        }

        result += next;

        //Insert created block into page
        var divBlock = document.getElementById("tier_container");
        divBlock.innerHTML = divBlock.innerHTML + result;
}