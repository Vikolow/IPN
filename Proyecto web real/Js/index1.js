/////////////////////////////////////////////////////////////////////////
//Generador de contraseñas 
////////////////////////////////////////////////////////////////////////
document.getElementById('gen').addEventListener("click",GenerarContraseñas)
document.getElementById('copiarBtn').addEventListener("click", copiarAlPortapapeles);

function GenerarContraseñas() {
    const longitud= parseInt(document.getElementById('longitud').value);
    if (longitud > 50) {
        document.getElementById('resultado').innerText = '¡La longitud no puede ser mayor a 50!';
        return;
    }
    const caracteres = [
        document.getElementById('incluirUppercase').checked ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '',
        document.getElementById('incluirLowercase').checked ? 'abcdefghijklmnopqrstuvwxyz' : '',
        document.getElementById('incluirNum').checked ? '0123456789' : '',
        document.getElementById('incluirSymb').checked ? '!@#$%^&*()_+[]{}|;:,.<>?' : ''
    ].join('');

    if (caracteres.length === 0) {
        document.getElementById('resultado').innerText = '¡Debes seleccionar al menos un tipo de carácter!';
        return;
    }

    let contraseña = '';
    for (let i = 0; i < longitud; i++) {
        contraseña += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    }

    document.getElementById('resultado').innerText = contraseña;
    document.getElementById('copiarBtn').style.display = 'inline-block';
}

function copiarAlPortapapeles() {
    const contraseña = document.getElementById('resultado').innerText;
    if (contraseña) {
        navigator.clipboard.writeText(contraseña).then(function() {
            alert("¡Contraseña copiada!");
        }).catch(function(error) {
            alert("Error al copiar: " + error);
        });
    }
}