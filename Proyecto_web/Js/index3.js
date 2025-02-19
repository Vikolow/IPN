/////////////////////////////////////////////////////////////////////////
// Conversor de decimal a binario y hexadecimal 
////////////////////////////////////////////////////////////////////////
document.querySelector(".convertir").addEventListener("click",convertirNumero)
function convertirNumero() {
    let decimal = document.getElementById('numero-decimal').value;
    
    if (isNaN(decimal) || decimal === ""|| decimal === "0" || decimal.includes(".") || decimal < 0) {
        document.getElementById('resultado-conversion').innerText = "Por favor, introduce un número válido.";
        return;
    }
    let binario = parseInt(decimal).toString(2); 
    let hexadecimal = parseInt(decimal).toString(16).toUpperCase();  

    let resultado = `Binario: ${binario}\nHexadecimal: ${hexadecimal}`;
    document.getElementById('resultado-conversion').innerText = resultado;
}
