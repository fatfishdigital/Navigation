/**
 * Navigation plugin for Craft CMS
 *
 * Navigation JS
 *
 * @author    Fatfish
 * @copyright Copyright (c) 2018 Fatfish
 * @link      https://fatfish.com.au
 * @package   Navigation
 * @since     1.0.0
 */
/*
code cleanup performed.
 */
$(document).ready(function () {

    var $modalInstnce;
    $tabledata = $('.sortable');
    $btnSave = $('#savemenu');
    $formElement = $('#form_element').html();
    $CreateMenu = $('#create_menu').html();
    $menuname = $('#menuname');
    $btnCustomUrl = $('#customPage');

/*
New menu from Entries list.
 */
    $('#addpage').on('click', function () {

        new Craft.BaseElementSelectorModal('craft\\elements\\Entry', {
            onSelect: function (element) {
                    for(var $i=0;$i<element.length;$i++) {
                        // var $tr = $("<tr>");
                        // var $td = $("<td>");
                        var $moveicon = $("<a>").addClass("move icon").attr({title: "Reorder", role: "button"});
                        var $listitem = $("<li>").addClass("mjs-nestedSortable-branch mjs-nestedSortable-expanded").attr({
                            id: "menuItem_" + element[$i].id,
                            title: element[$i].label,
                            style: "display:list-item"
                        });
                        var $menuDiv = $("<div>").addClass("element small hasstatus menuDiv");
                        var $editlink = $("<a>").addClass("menusettings").attr({
                            title: "setting",
                            role: "button",
                            id: "menuItem_" + element[$i].id,
                            onclick: 'updateNode($(this))'
                        }).html(element[$i].label);
                        var $menulabel = $("<span>").addClass("menulabel").append($editlink).append("&nbsp;");
                        var $deletelink = $("<a>").addClass("delete icon deletenode").attr({
                            title: "delete",
                            role: "button",
                            id: "menuItem_" + element[$i].id,
                            onclick: 'removeMenuNode($(this))',
                            style: 'position:relative;left:10px;'
                        });
                        $listitem.append($moveicon);
                        $menuDiv.append($menulabel);
                        $menuDiv.append($deletelink);
                        $listitem.append($menuDiv);
                        // $td.append($listitem);
                        // $tr.append($td);
                        $tabledata.append($listitem);
                    }
                         },
            multiSelect: true
        });

    });

    $btnSave.on('click', function () {
        if($('#menuname').val()==="")
        {
          Craft.cp.displayNotification('error',"Cannot Save Empty Menu !!!");
            return;
        }
        else if(!$('ol').children().length>0)
        {
           Craft.cp.displayNotification('error','You dont have any menu item in the list');
           return;
        }
        else{

        }
        var $postData = [{menuname:$('#menuname').val(),siteId:Craft.siteId}];
        var $SerializedMenu = $('ol.sortable').nestedSortable('toArray');
        var $id= $('#menuid').val();
        var $htmlmenu=$.trim($('#navigation-menu').html());
         Craft.postActionRequest('/craftnavigation/save',{menuname:$postData,menuArray:$SerializedMenu,id:$id,menuhtml:$htmlmenu},function (response, status) {
           if(response==1)
           {
               Craft.cp.displayNotice('Menu Saved');
           }
           else
           {
               Craft.cp.displayAlerts('Error on Saving Menu');
           }
        });
    });
/*
Custom menu
 */
    $btnCustomUrl.on('click', function (e) {


        $formBody = $('<div class="modal fitted"/>');

        $($formElement).appendTo($formBody);

        $modal = new Garnish.Modal($formBody, {
            onShow: function () {
                $CustomButton = $('#BtnCustomUrl');
                $CustomButton.on('click', function () {
                    $randomId = Math.floor((Math.random() * 100) + 1);
                    // var $tr = $("<tr>");
                    // var $td=$("<td>");
                    var $moveicon = $("<a>").addClass("move icon").attr({title:"Reorder",role:"button"});
                    var $listitem    = $("<li>").addClass("mjs-nestedSortable-branch mjs-nestedSortable-expanded").attr({id:"menuItem_"+$randomId,title:$('#name').val(),url:$('#url').val(),style:"display:list-item"});
                    var $menuDiv     = $("<div>").addClass("element small hasstatus menuDiv");
                    var $editlink    = $("<a>").addClass("menusettings").attr({title:"setting",role:"button",id:"menuItem_"+$randomId,onclick:'updateNode($(this))',url:$('#url').val()}).html($("#name").val());
                    var $menulabel   = $("<span>").addClass("menulabel").append($editlink).append("&nbsp;");
                    var $deletelink  = $("<a>").addClass("delete icon deletenode").attr({title:"delete",role:"button",id:"menuItem_"+$randomId,onclick:'removeMenuNode($(this))',style:'position:relative;left:10px;'});
                   $listitem.append($moveicon);
                    $menuDiv.append($menulabel);
                    $menuDiv.append($deletelink);

                    $listitem.append($menuDiv);
                    // $td.append($listitem);
                    // $tr.append($td);

                    $tabledata.append($listitem);

                    $modal.hide();
                    $modal.destroy();
                });
                $('#exit').on('click',function () {
                    $modal.hide();
                    $modal.destroy();
                });
            },
            onHide:function()
            {
                $modal.destroy();
            }


        });

    });


    $('#NewMenu').on('click', function () {
        $modal = $('<div class="modal fitted"/>');
        $($CreateMenu).appendTo($modal);
        $modal = new Garnish.Modal($modal, {
            onShow: function () {
                $modalInstnce=$modal;
                $('#MenuBtn').on('click', function () {
                    $('.namelabel').html($('#name').val());
                    $menuname.val($('#name').val());

                    Craft.postActionRequest('/craftnavigation/menusave',{data:$('#name').val(),siteid:Craft.siteId},function (response,status) {
                                    if(status=="success")
                        {

                            Craft.cp.displayNotice("Menu Created Successfully");
                            $modal.hide();
                            $modal.destroy();
                           /*

                           TODO ::
                           we need to optimize this code,
                           ideally menusave function should be able to render Template with parameters
                           since its frontend ajax request to controller it can only render Template but passing variables
                           is not working.
                           Waiting for Craft People to respond to this issues.
                            */

                            $(location).attr('href', '/admin/craftnavigation/edit/'+response);
                        }
                        else
                        {
                            Craft.cp.displayAlerts("Cannot Create Menu !!!");
                            $modal.hide();
                            $modal.destroy();
                        }
                    });

                });
                $('#exit').on('click',function () {
                    $modal.hide();
                    $modal.destroy();
                });
            },
            onHide: function () {
                $modal.destroy();
            },
            onCancel:function () {
                $modal.destroy();
            }
        });
    });

    /*
    Delete Menu and its Submenu
     */

$('.DeleteNav').on('click',function () {
   var $MenuName = $(this).data('title');
   $deleteModal = $('<div class="modal fitted"/>');
    var $ModalBody = $('#DeleteMenu').html();
    $deleteModal.append($ModalBody);
   var $modal=new Garnish.Modal($deleteModal,{
        onShow : function () {
            $('#MenuBtn').on('click',function () {
              var $id = $('.DeleteNav').data('id');
              Craft.postActionRequest('/craftnavigation/delete',{id:$id},function (response,status) {
                  if(status=="success")
                  {
                      Craft.cp.displayNotice("Menu Deleted Successfully !!");
                      $(location).attr('href', '/admin/craftnavigation');
                  }
                  else {
                      Craft.cp.displayNotice("Cannot Delete Menu !");

                  }
              });
              $modal.hide();
              $modal.destroy();

            });
            $('#exit').on('click',function () {
                $modal.hide();
                $modal.destroy();
            });
        },
       onHide: function () {
           $modal.destroy();

       }

   });

});

   /*
        Adding Navigation rename feature
 */
    $('.EditNav').on('click',function () {
        var $EditMenuhtml = $('#EditMenuName').html();
        var $EditModel = $('<div class="modal fitted"/>');
        var $name = $(this).attr('title');
        var $id = $(this).data('id');

        $EditModel.append($EditMenuhtml);
        var $modal = new Garnish.Modal($EditModel,{
            
            onShow: function () {
                $('#name').val($name);
                $('#EditMenuBtn').on('click',function () {

                    var $newName = $('#name').val();
                    Craft.postActionRequest('/craftnavigation/rename',{id:$id,name:$newName},function () {
                        window.location.reload(true);
                        $modal.hide();
                        $modal.destroy();
                    });
                    $modal.hide();
                    $modal.destroy();
                });

                $('#exit').on('click',function () {
                    $modal.hide();
                    $modal.destroy();
                });

            }
        });
    });

    
});
/*
Remove menu from list.
 */
function removeMenuNode($this) {
  var  $Modal = $('<div class="modal fitted"/>');
  var  $deletenode = $('#remove_node').html();
   $Modal.append($deletenode);
   var  $DeleteNodeModal = new Garnish.Modal($Modal,{

        onShow:function () {
            $('#Delete').on('click',function () {
                $id='#'+$this.attr('id');
                Craft.postActionRequest('/craftnavigation/deletenode',{id:$this.attr('id')});
                $($id).remove();
                var $postData = [{menuname:$('#menuname').val(),siteId:Craft.siteId}];
                var $SerializedMenu = $('ol.sortable').nestedSortable('toArray');
                var $id= $('#menuid').val();
                var $htmlmenu=$.trim($('#navigation-menu').html());
                Craft.postActionRequest('/craftnavigation/save',{menuname:$postData,menuArray:$SerializedMenu,id:$id,menuhtml:$htmlmenu});
                $DeleteNodeModal.hide();
                $DeleteNodeModal.destroy();
            });
            $('#exit').on('click',function () {
                $DeleteNodeModal.hide();
                $DeleteNodeModal.destroy();
            });
        },
        onHide:function () {
           $DeleteNodeModal.destroy();
        }




    });

}

/*
    when any node is updated this will change the value of each updated node.
 */
function updateNode($this)
{


    $id = '#'+$($this).attr('id');
    $menuname = $($id).find('div').find('span').find('a').html();
    $url = $($id).attr('url');
    $formBody = $('<div class="modal fitted"/>');
    $($formElement).appendTo($formBody);
    $modal = new Garnish.Modal($formBody, {
        onShow: function () {

            $('#name').val($menuname);
            if($url=='' || typeof($url)=='undefined')
            {
                $('#url').attr('readonly','true');
            }else {
                $('#url').val($url);
            }
           var $CustomButton = $('#BtnCustomUrl');
            $CustomButton.on('click', function () {
               $($id).children('div').children('span').find('a').html($('#name').val());
               $($id).attr('title',$('#name').val());
               $($id).attr('url',$('#url').val());
               $modal.hide();
               $modal.destroy();
            });
            $('#exit').on('click',function () {
               $modal.hide();
               $modal.destroy();
            });
        },
        onHide:function () {
            $modal.destroy();
        }


    });

}
