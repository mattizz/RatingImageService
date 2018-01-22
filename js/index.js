var sh=0;

function login(){
    if(sh==0){
        $("#login_panel").show();
        sh=1;
    }else if(sh==1){
        $("#login_panel").hide()
        sh=0;
    }   
}