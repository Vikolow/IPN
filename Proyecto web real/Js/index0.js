document.querySelector(".calcular").addEventListener("click", calcularIP);

// Función para convertir de decimal a binario
function decimalABinario(octeto) {
    return octeto.toString(2).padStart(8, '0');
}

// Función para convertir de binario a decimal
function binarioADecimal(binario) {
    return parseInt(binario, 2);
}

// Función para hacer un AND bit a bit
function operacionAND(ip, mascara) {
    return ip.map((octeto, i) => octeto & mascara[i]);
}

// Función para calcular la dirección de red y broadcast
function calcularRedYBroadcast(ip, mascara) {
    const direccionRed = operacionAND(ip, mascara);
    const direccionBroadcast = direccionRed.map((octeto, i) => octeto | (~mascara[i] & 255));
    
    return {
        red: direccionRed.join('.'),
        broadcast: direccionBroadcast.join('.'),
    };
}

// Función principal para calcular la IP
function calcularIP() {
    const ip = document.getElementById('ip').value.split('.').map(Number);
    const mascara = document.getElementById('subnet').value.split('.').map(Number);
    
    const { red, broadcast } = calcularRedYBroadcast(ip, mascara);
    
    // Mostrar los resultados
    document.getElementById('resultado-ip').innerText = `Red: ${red}\nBroadcast: ${broadcast}`;
}