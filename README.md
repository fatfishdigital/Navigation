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

        composer require https://github.com/fatfishdigital/navigation

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Navigation.

## Navigation Overview

Navigation is simple menu builder for Craft CMS 3.x

   ![Screenshot](resources/img/Navigation.png)


## Templating 

**craft.Navigation.render()**

craft.Navigation.render() lets you build menu with inbuilt html. It takes two parameter which are Menu Name (menuhandle)

and menu styling option (style option is optional). 
    
        {{craft.Navigation.render('Footer Nav',{ulClass:'topnav',activeClass:'active'})}}

Navigation menu also lets you build your menu using custom html, This will entirely based on your own html and css,

you can use it via twig macro. 

Inorder to achieve this you need to use **craft.Navigation.getRawNav(MenuName)** 

This will let you build your own html menu with twig macro. Below is twig macro code which will help you to build your menu.

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
