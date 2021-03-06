define(['angular'], function (ng) {
    'use strict';
    
    return ng.module('app.controllers', ['flow']).config(function($interpolateProvider, $compileProvider){
        $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
        $compileProvider.imgSrcSanitizationWhitelist(/^\s*(https|ftp|mailto|file|tel|data)/);
    });
});
