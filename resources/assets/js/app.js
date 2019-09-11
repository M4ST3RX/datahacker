
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Computer from './components/Computer.vue';
import Terminal from './components/Terminal.vue';
import TaskManager from './components/TaskManager.vue';
import History from './components/History.vue';
import Files from './components/Files.vue';
import TrayIcon from './components/TrayIcon.vue';
import Tray from './components/Tray.vue';

var Vue = require('vue');

Vue.component('computer', Computer);
Vue.component('terminal', Terminal);
Vue.component('task-manager', TaskManager);
Vue.component('history', History);
Vue.component('files', Files);
Vue.component('tray-icon', TrayIcon);
Vue.component('tray', Tray);

let vue = new Vue({ el: "#app" });
window.vue = {};

vue.$children[0].$children.forEach(function(el){
    if(el !== undefined){
        window.vue[el._data.name] = el;
    }
});

let tray = {};

vue.$children[0].$children.forEach(function(element){
    if(element.$el.id == "tray"){
        tray = element;
    }
});


import $ from 'jquery';
window.$ = window.jQuery = $;

import 'jquery-ui/ui/widgets/resizable';
import 'jquery-ui/ui/widgets/draggable';

window.terminal_cache = [];
window.local_files_cache = [];
window.remote_files_cache = [];

let sPositions = localStorage.positions || "{}",
    positions = JSON.parse(sPositions);
$.each(positions, function (id, pos) {
    $("#" + id).css(pos);
});

localStorage.setItem("connectedTo", "local");

function extend(obj, src) {
    for (var key in src) {
        if (src.hasOwnProperty(key)) obj[key] = src[key];
    }
    return obj;
}

function updateLocalFileCache(){
    $.ajax("/api/computer/files", {
        type: "GET"
    }).done(function(data){
        if(data){
            window.local_files_cache = JSON.parse(data['files']);
            updateFileManager();
        } else {
            console.log('PHP error.');
        }
    });
}

function updateFileManager(){
    let element = $('#files .block');
    let table = "<table width=\"100%\"><th>Name</th><th>Modified At</th><th>Type</th><th>Level</th>";
    window.local_files_cache.forEach(function(obj){
        let date = new Date(obj.updated_at);
        let day = date.getDate();
        let month = date.getMonth() + 1;
        let year = date.getFullYear();
        if(day < 10){
            day = "0" + day;
        }
        if(month < 10){
            month = "0" + month;
        }
        let modified_date = day + "/" + month + "/" + year;
        table += "<tr><td>"+obj.file_name+"."+obj.type.extension.toLowerCase()+"</td><td>"+modified_date+"</td><td>"+obj.type.type+"</td><td>"+obj.file_level+"</td></tr>";
    });
    table += "</table>";
    element.html(table);
}

$(function() {
    updateLocalFileCache();
    let start_term_element = $("#start_term");
    start_term_element.prepend(localStorage.getItem("connectedTo"));
    $("#term_command").css("padding-left", (start_term_element.width() + 5).toString() + "px");
    $('.ui-resizable').resizable({
        handles: "n, e, s, w, ne, se, sw, nw",
        start: function (event, ui) {
            tray.selectMenu(this.id, camelCase(this.id));
        },
        stop: function (event, ui) {
            positions[this.id] = extend(ui.position, ui.size);
            localStorage.positions = JSON.stringify(positions);
        }
    });
    $('.ui-draggable').draggable({
        handle: ".window-header",
        start: function (event, ui) {
            tray.selectMenu(this.id, camelCase(this.id));
        },
        stop: function (event, ui) {
            positions[this.id] = extend(ui.position, {width: ui.helper.width(), height: ui.helper.height()});
            localStorage.positions = JSON.stringify(positions);
        }
    });
    /*if(localStorage.selectedMenu !== undefined){
        $('#' + localStorage.selectedMenu).addClass('menu-selected');
    }*/
});

function camelCase(str){
    let parts = str.split(/[-_]+/);
    let camelCasedWord = "";
    parts.forEach(function(word){
        if(word != parts[0]){
            word = word.charAt(0).toUpperCase() + word.slice(1);
        }
        camelCasedWord += word;
    });

    return camelCasedWord;
}

/*$('#term_command').keyup(function(event) {
    if (event.which === 13) {
        let command_parts = $('#term_command').val().toString().split(" ");
        let cmd = command_parts[0];
        let cmd_args = [];
        if(command_parts.length > 1){
            cmd = command_parts.shift();
            cmd_args = command_parts;
        }
        $.ajax("/api/terminal", {
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                cmd: cmd,
                args: cmd_args
            }
        }).done(function(data){
            data = JSON.parse(data);
            outputTerminal(data);
            if(data){
                console.log(data);
                if(data['function'] != undefined){
                    console.log("dsadasdas");
                    if(data['function'] == "setConnectedTo"){
                        console.log("asdsad");
                        localStorage.connectedTo = data['ip'];
                        $("#start_term").html(localStorage.connectedTo + "<i class=\"terminal_chevron fa fa-chevron-right\" style=\"font-size:12px;\" aria-hidden=\"true\"></i>");
                        $("#term_command").css("padding-left", ($("#start_term").width() + 5).toString() + "px");
                    }
                }
            } else {
                console.log('PHP error.');
            }
        });
    }
});*/

/*function outputTerminal(data) {
    let term_command = $('#term_command');
    let terminal_dump = $("#term_dump");
    let dialog_term = $('#dialog_term');
    let cmd = term_command.val().toString();
    event.preventDefault();
    terminal_dump.append($('#start_term').html() + '&nbsp;' + cmd.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') + '<br>');
    if (data['error']) {
        terminal_dump.append('<br><span style="color: #f99f4a; font-weight: bold;">' + data['msg'] + '</span><br><br>');
    } else {
        terminal_dump.append('<br>' + data['msg'] + '<br><br>');
        if (data['process_cpu']) {
            $('#task_manager .block #process_cpu').append(
                "<div class=\"process\">" +
                "<table style='width: 100%; height: 100%;'>" +
                "<tbody><tr>" +
                "<td style=\"cursor: pointer;\" id=\"process_" + data['process_data']['id'] + "\" class=\"process_action_container\" width=\"10%\">" +
                "<i class=\"fa fa-times process_action\" aria-hidden=\"true\"></i>" +
                "</td>" +
                "<td width=\"80%\" style=\"background: rgba(0,166,198,0.5);\">" +
                "<div id=\"process_change_" + data['process_data']['id'] + "\" data='running' data='"+ data['process_data']['type'] +"' class=\"process_fill progress-bar progress-bar-striped progress-bar-animated\" role=\"progressbar\" style=\"background-color: rgb(0, 100, 130); transition: none !important;height:100%;width: 0%\">" +
                "<div class=\"progress_item\" style=\"width:100%;overflow:hidden;\">" +
                "<span>" + data['process_data']['ip'] + "</span>" +
                "</div>" +
                "</div>" +
                "</td>" +
                "<td id=\"process_id_" + data['process_data']['id'] + "\" class=\"process_type\" width=\"10%\"><i class=\"fas fa-check\"></i></td>" +
                "</tr>" +
                "</tbody></table>" +
                "</div>"
            );
        } else if(data['process_network']) {

        }
    }
    dialog_term.parent()[0].scrollTop = dialog_term.parent()[0].scrollHeight;
    term_command.val('');
    term_command.focus();

    while (terminal_dump.contents().length > 100) {
        terminal_dump.contents().first().remove();
    }
}*/

function select_top_window(element, menu_element){
    let windows = element.siblings('.window');
    element.css('z-index', windows.addBack().length + 1);
    windows.each(function(index){
        let currentWindow = $(this);
        currentWindow.css('z-index', currentWindow.css('z-index') - 1);
    });

    /*menu_element.siblings('.main-menu').each(function(index){
        $(this).removeClass('menu-selected');
    });
    menu_element.addClass('menu-selected');*/
    //localStorage.selectedMenu = menu_element.attr('id');
}

$(document).on('click', '.window', function(){
    select_top_window($(this), $('#menu_'+$(this).attr('id')));
});

$(document).on('click', '.main-menu', function(){
    let id = $(this).attr('id').split('_');
    id.shift();
    if(jQuery.type(id) == "array"){
        id = id.join('_');
    }
    /*if(positions[id]['display'] !== undefined || positions[id]['display'] == "none"){
        let openCss = {display: "block"};
        positions[id] = extend(positions[id], openCss);
        localStorage.positions = JSON.stringify(positions);
        $("#" + id).css(openCss);
    }*/
    select_top_window($('#'+id), $(this));
});

/* $(document).on('click', '.fa-window-close', function(){
    let closedCss = {display: "none"};
    let element = $(this);
    let parent = element.closest('.window');
    positions[parent.attr('id')] = extend(positions[parent.attr('id')], closedCss);
    localStorage.positions = JSON.stringify(positions);
    parent.css(closedCss);
}); */

/*$(document).on('click', '.process_type.done', function(){
    let element = $(this);
    let id = element.attr('id').split('_')[2];
    if (id !== undefined) {
        $.ajax("/api/taskmanager/finish", {
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: id
            }
        }).done(function (data) {
            if (data) {
                outputTerminal(JSON.parse(data));
                element.closest('div.process').remove();
            } else {
                console.log('PHP error.');
            }
        });
    }
});*/

/*$(document).on('hover', '.main-menu', function(){
    $(this).css('background-color', 'brown');
});*/

/*$(document).on('click', '.process_action_container', function() {
    let element = $(this);
    let element_id = element.attr('id');
    let str = element_id.split('_');
    if (str[1] !== undefined) {
        $.ajax("/api/taskmanager/delete", {
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: str[1]
            }
        }).done(function (data) {
            if (data) {
                element.closest('div.process').remove();
            } else {
                console.log('PHP error.');
            }
        });
    }
});*/

$(document).on('click', '#task_manager .block .tabs .tab-cpu', function(){
    let element = $(this);
    let block = $('#task_manager .block');
    if(!element.hasClass('tab-style-selected')){
        element.addClass('tab-style-selected');
        block.children('.tabs').children('.tab-network').removeClass('tab-style-selected');
        block.children('#process_network').addClass('hide');
        block.children('#process_cpu').removeClass('hide');
    }
});

$(document).on('click', '#task_manager .block .tabs .tab-network', function(){
    let element = $(this);
    let block = $('#task_manager .block');
    if(!element.hasClass('tab-style-selected')){
        element.addClass('tab-style-selected');
        block.children('.tabs').children('.tab-cpu').removeClass('tab-style-selected');
        block.children('#process_cpu').addClass('hide');
        block.children('#process_network').removeClass('hide');
    }
});

function update_progress_bar() {
    $('.process_fill').each(function( index ) {
		let currentObject = $(this);
        if (currentObject.attr('data') === 'running') {
            let row = currentObject.closest("tr");
            let maxWidth = (row.width() - row.children(".process_type").width()) - row.children(".process_action_container").width();
            if (currentObject.width() < maxWidth) {
                currentObject.animate({
                    width: '100%'
                }, {
                    duration: (10 * 1000),
                    specialEasing: {
                        width: "linear",
                    }
                });
            } else {
                currentObject.attr('data', 'done');
                currentObject.parent().next().addClass('done');
            }
        }
    });
}

$(document).ready(function () {
    setInterval(update_progress_bar, 100);
});
