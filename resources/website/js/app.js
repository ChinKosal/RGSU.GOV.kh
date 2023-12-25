window.require = () => {};
import $ from "jquery";
window.$ = window.jQuery = $;

require("./bootstrap");

import Axios from "axios";
window.Axios = Axios;

import Alpine from "alpinejs";
import form from './alpine_plugin/form';
import plugin from "./alpine_plugin/plugin";
Alpine.plugin(form);
Alpine.plugin(plugin);
window.Alpine = Alpine;

import anime from "animejs/lib/anime.es.js";
window.anime = anime;

import * as feather from "feather-icons";
window.feather = feather;

import { Fancybox } from "@fancyapps/ui";
window.Fancybox = Fancybox;
import "@fancyapps/ui/dist/fancybox.css";

import LazyLoad from "vanilla-lazyload";
window.LazyLoad = LazyLoad;

import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
window.Swiper = Swiper;

import AOS from 'aos';
import 'aos/dist/aos.css';
window.AOS = AOS;

import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/src/sweetalert2.scss';
window.Swal = Swal;

import ScrollTrigger from 'gsap/ScrollTrigger';
import gsap from 'gsap';
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

import * as fontawesome from '@fortawesome/fontawesome-free';
import '@fortawesome/fontawesome-free/css/all.min.css';
window.fontawesome = fontawesome;

require('./package/mask-js/jquery.mask');