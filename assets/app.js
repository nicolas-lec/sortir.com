/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';



let aujourdhui = Date.now()
let dateSortie = new Date(2018, 8, 22, 15, 0, 0)


if (dateSortie < aujourdhui)
{
    alert("La date doit être supérieur");
}
else
{
    alert("La date est ok");
}

