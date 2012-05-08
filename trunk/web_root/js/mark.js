var marks={};
function SetMark(aid,cid,pos,uid,title){
    if ($.cookie("marks") != null && $.cookie("marks") != "") {
        marks=$.evalJSON($.cookie("marks"));
    }else{
        marks={};
    }
    if(marks[aid]==undefined){
        marks[aid]={};
    }
    marks[aid]={'cid':cid, 'pos':pos, 'uid':uid, 'title':title};
//    _trace(marks, 'alert');
    $.cookie('marks', $.toJSON( marks ), { expires: 360000, path: '/' });
    init();
//    _trace($.evalJSON($.cookie("marks")), 'alert');
}

function GetMarks(){
    var marks={};
    if ($.cookie("marks") != null && $.cookie("marks") != "") {
        marks=$.evalJSON($.cookie("marks"));
    }
//    _trace(marks);
    return marks;
}

function init(){
    var cmarks=GetMarks();
    var h='';
    var l='';
    for(aid in cmarks){
        if (cmarks[aid] != null && cmarks[aid] != "") {
            if(cmarks[aid].cid>0){
                l='['+cmarks[aid].pos+'楼]';
                var page=((cmarks[aid].cid-1)/10|0)+1;
            }else{
                l='[顶楼]';
                var page=1;
            }

            h += '<div id="cmk_'+aid+'"><a href="'+'/article/'+aid+'/'+page+'.html#p'+cmarks[aid].pos+'">'+cmarks[aid].title+l+'</a>&nbsp;'
                +  '<span style="color: red;padding:2px;font-size: 18px;cursor:pointer;" onclick="DelMark('+aid+');">x</span><br />';
        }
    }
    //_trace(cmarks);
    $("#ec").html(h);
}

function DelMark(aid){
    marks=GetMarks();
    if (marks[aid] != null && marks[aid] != "") {
        marks[aid]=null;
    }
    $.cookie('marks', $.toJSON( marks ));
    $("#cmk_"+aid).remove();
}