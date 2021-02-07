console.log('labas');
import './styles/app.scss';
import 'bootstrap';
const $ = require('jquery');
const summernote = require('summernote');
$('#summernote').summernote();

console.log('summernote');

//hamburgerio click
    const hamburger = document.querySelector('.mob-menu .hamburger');
    const nav = document.querySelector('.mob-menu nav');
        hamburger.addEventListener('click', () => {
            nav.classList.toggle('visible');
        });