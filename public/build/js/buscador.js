function iniciarApp(){buscarFecha()}function buscarFecha(){document.querySelector("#fecha").addEventListener("input",(function(t){const n=t.target.value;if(""===n){var e=new Date;day=e.getUTCDay().toString().padStart(2,"0"),month=(e.getMonth()+1).toString().padStart(2,"0"),year=e.getFullYear().toString(),e=`${year}-${month}-${day}`,window.location="?fecha="+e}else window.location="?fecha="+n}))}document.addEventListener("DOMContentLoaded",(function(){iniciarApp()}));