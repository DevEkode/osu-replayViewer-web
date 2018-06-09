function showDim(){
  document.getElementById("dimValue").innerHTML = document.getElementById("dimRange").value+"%";

  var image = document.getElementById("dimPreview");
  var amount = map(document.getElementById("dimRange").value,0,100,1,0);
  image.setAttribute('style', 'filter:brightness(' + amount + '); -webkit-filter:brightness(' + amount + '); -moz-filter:brightness(' + amount + ')');
}

function map(s,a1,a2,b1,b2)
{
    return b1 + (s-a1)*(b2-b1)/(a2-a1);
}

function updateCustomSkin(){
  var enable = document.getElementById("checkBox");
  if(enable.checked == true){
    document.getElementById("skinsSelector").disabled = false;
  }else{
    document.getElementById("skinsSelector").disabled = true;
  }
}
