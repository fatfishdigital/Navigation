# Navigation plugin for Craft CMS 3.x

Craft navigation plugin for the website.

![Screenshot](resources/img/pluginlogo.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require fatfish/navigation

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Navigation.

## Navigation Overview

Navigation is a simple menu builder for Craft CMS 3.x

   ![Screenshot](resources/img/Navigationplugin.png)
   
   1. Click on create Menu, Give your menu a name.
   2. Choose your entries by clicking on entry button or click on external link if you want add external link
   3. Save Menu 
   
   Voila you are done !.


## Templating 

**craft.Navigation.render()**

craft.Navigation.render() lets you build a menu with pre-built static templates. This function takes two parameters, menu name "menu_name" and menu styling options.

Menu style options contains arrays which have a 'div', 'ul', 'li', and 'a' element. Each element can have their own CSS class.
    
        {{craft.Navigation.render('menu_name',{  wrapperClass : 'navbar',
                                                   ulClass: 'navbar-nav',
                                                   listClass: 'nav-item',
                                                   linkClass: 'nav-link'})}}

This plugin also lets you build menus using your own HTML and CSS, 
you can use it via Twig Macro. 

Inorder to achieve this you need to use **craft.Navigation.getRawNav(MenuName)** 

This will let you build your own HTML menu with Twig Macros. 

Sample custom menu:

    {% import _self as macros %}
    {% macro menu(node) %}
        {% import _self as macros %}
        {% set Grandchildren = craft.Navigation.renderChildren(node) %}
        {% if Grandchildren | length > (0) %}
            <ul>
                {% for grandchildren in Grandchildren  %}
                    <li>
                        <a href="{% if craft.entries.id(grandchildren.NodeId).one().uri is defined %}/{{craft.entries.id(grandchildren.NodeId).one().uri}}{% else %}{{ grandchildren.menuUrl }}  {% endif %}">{{ grandchildren.NodeName }}</a>
                        {{ macros.menu(grandchildren.NodeId) }}
                    </li>
                {% endfor %}
            </ul>
        {% endif %}
    {% endmacro %}
    
    
    <ul>
    {% set MenuNodes=craft.Navigation.getRawNav('MenuName') %}
       {% if MenuNodes is defined  %}
            {% if MenuNodes is iterable %}
    
                {% for MenuNode in MenuNodes %}
    
                       {% if MenuNode.ParenNode == (0)  %}
    
                           {% if craft.Navigation.renderChildren(MenuNode.NodeId) is iterable %}
                               <li>
    
                                   <a href="{% if craft.entries.id(MenuNode.NodeId).one().uri is defined %}/{{craft.entries.id(MenuNode.NodeId).one().uri}}{% else %}{{ MenuNode.menuUrl }}  {% endif %}">{{ MenuNode.NodeName }}</a>
                                  
                                 {% if craft.Navigation.renderChildren(MenuNode.NodeId) | length %}
                                   <ul>
                                      {% for childrenMenu in  craft.Navigation.renderChildren(MenuNode.NodeId) %}
                                          <li>
                                              <a href="{% if craft.entries.id(childrenMenu.NodeId).one().uri is defined %}/{{craft.entries.id(childrenMenu.NodeId).one().uri}}{% else %}{{ childrenMenu.menuUrl }}  {% endif %}">{{ childrenMenu.NodeName }}</a>
                                              {{ macros.menu(childrenMenu.NodeId) }}
    
                                          </li>
                                       {% endfor %}
    
                                   </ul>
                                     {% endif %}
                               </li>
                               {% else %}
    
                            {% endif %}
                    {% else %}
                    {% endif %}
    
                {% endfor %}
                {% endif %}
    {% else %}
     {% endif %}
    </ul>


Brought to you by [Fatfish](https://fatfish.com.au)
