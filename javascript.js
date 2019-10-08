function O(i) { return typeof i == 'object' ? i : document.getElementById(i) }
function S(i) { return O(i).style                                            }
function C(i) { return document.getElementsByClassName(i)                    }

function ajaxRequest()
{
  try { var request = new XMLHttpRequest() }
  catch(e1) {
    try { request = new ActiveXObject("Msxml2.XMLHTTP") }
    catch(e2) {
      try { request = new ActiveXObject("Microsoft.XMLHTTP") }
      catch(e3) {
        request = false
  } } }
  return request
}

