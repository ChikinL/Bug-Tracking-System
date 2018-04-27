$(document).ready(function ()
{
    for (var i = document.links.length; i-- > 0; )
        if (/^X$/g.test(document.links[i].innerHTML))
            document.links[i].style.color = 'red';
});