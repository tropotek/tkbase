/**
 * This file is the main file for the npm package manager
 * npm.js
 */
import $ from 'jquery';
global.jQuery = $;
global.$ = $;

import 'bootstrap';
import '../scss/app.scss';

/**
 * App Lib Imports
 */
import scrollToTop from './scrolltop';
global.scrollToTop = scrollToTop;






