/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import './styles/volt.scss';

// todo: check how to add this @ in front and use this also in VUE when importing something in classes?
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'popper.js/dist/umd/popper.min.js'
import 'bootstrap/dist/js/bootstrap.min.js';
import 'onscreen/dist/on-screen.umd.js'
import "nouislider/distribute/nouislider.min.js";
import "jarallax/dist/jarallax.min.js";
import "smooth-scroll/dist/smooth-scroll.polyfills.min.js";
import "countup.js/dist/countUp.umd.js";
import "notyf/notyf.min.js";
import "chartist/dist/chartist.min.js";
import "chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js";
import "vanillajs-datepicker/dist/js/datepicker.min.js";
import "simplebar/dist/simplebar.min.js";

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/app.js');
