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
                       var $listitem    = $("<li>").addClass("mjs-nestedSortable-branch mjs-nestedSortable-expanded").attr({id:"menuItem_"+element[0].id,title:element[0].label,style:"display:list-item"});
                       var $menuDiv     = $("<div>").addClass("menuDiv");
                       var $menulabel   = $("<span>").addClass("menulabel").html(element[0].label);
                       var $deletelink  = $("<a>").addClass("delete icon deletenode").attr({title:"delete",role:"button",id:"menuItem_"+element[0].id,onclick:'removeMenuNode($(this))'});
                       var $editlink    = $("<a>").addClass("settings icon menusettings").attr({title:"setting",role:"button",id:"menuItem_"+element[0].id,onclick:'updateNode($(this))'});
                       $menuDiv.append($menulabel);
                       $menuDiv.append($deletelink);
                       $menuDiv.append($editlink);
                       $listitem.append($menuDiv);
                       $tabledata.append($listitem);
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
         Craft.postActionRequest('/navigation/save',{menuname:$postData,menuArray:$SerializedMenu,id:$id,menuhtml:$htmlmenu},function (response, status) {
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
                    var $listitem = $("<li>").addClass("mjs-nestedSortable-branch mjs-nestedSortable-expanded").attr({style:"display:list-item",id:"menuItem_"+$randomId,title:$('#name').val(),url:$('#url').val()});
                    var $menuDiv  = $("<div>").addClass("menuDiv");
                    var $menulabel= $("<span>").addClass("menulabel").html($("#name").val());
                    var $deletelink  = $("<a>").addClass("delete icon deletenode").attr({title:"delete",role:"button",id:"menuItem_"+$randomId,onclick:'removeMenuNode($(this))'});
                    var $editlink    = $("<a>").addClass("settings icon menusettings").attr({title:"setting",role:"button",id:"menuItem_"+$randomId,onclick:'updateNode($(this))'});
                    $menuDiv.append($menulabel);
                    $menuDiv.append($deletelink);
                    $menuDiv.append($editlink);
                    $listitem.append($menuDiv);
                    $tabledata.append($listitem);
                    $modal.hide();
                    $modal.destroy();
                });
            }


        });

    });


    $('#NewMenu').on('click', function () {
        $modal = $('<div class="modal fitted"/>');
        $($CreateMenu).appendTo($modal);
        $modal = new Garnish.Modal($modal, {
            onShow: function () {
                $('#MenuBtn').on('click', function () {
                    $('.namelabel').html($('#name').val());
                    $menuname.val($('#name').val());
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
              Craft.postActionRequest('/navigation/delete',{id:$id},function (response) {
                  if(response)
                  {
                      Craft.cp.displayNotice("Menu Deleted Successfully !!");
                      location.reload();
                  }
                  else {
                      Craft.cp.displayNotice("Cannot Delete Menu !");
                      location.reload();
                  }
              });
              $modal.hide();
              $modal.destroy();
            });
        },
       onHide: function () {
           $modal.destroy();

       }

   });

});
});
/*
Remove menu from list.
 */
function removeMenuNode($this) {

    $id='#'+$this.attr('id');
    Craft.postActionRequest('/navigation/deletenode',{id:$this.attr('id')});
    $($id).remove();
    var $postData = [{menuname:$('#menuname').val(),siteId:Craft.siteId}];
    var $SerializedMenu = $('ol.sortable').nestedSortable('toArray');
    var $id= $('#menuid').val();
    var $htmlmenu=$.trim($('#navigation-menu').html());
    Craft.postActionRequest('/navigation/save',{menuname:$postData,menuArray:$SerializedMenu,id:$id,menuhtml:$htmlmenu});
}

/*
    when any node is updated this will change the value of each updated node.
 */
function updateNode($this)
{


    $id = '#'+$($this).attr('id');
    $menuname = $($id).find('div').find('span').html();
    $url = $($id).data('url');

    $formBody = $('<div class="modal fitted"/>');
    $($formElement).appendTo($formBody);
    $modal = new Garnish.Modal($formBody, {
        onShow: function () {

            $('#name').val($menuname);
            $('#url').val($url);
           var $CustomButton = $('#BtnCustomUrl');
            $CustomButton.on('click', function () {
               $($id).find('div').find('span').html($('#name').val());
               $($id).attr('title',$('#name').val());
               $($id).attr('url',$('#url').val());
               $modal.hide();
               $modal.destroy();
            });
        },
        onHide:function () {
            $modal.destroy();
        }


    });

}