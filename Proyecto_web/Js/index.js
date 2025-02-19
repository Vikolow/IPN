// Al agregar el evento click a los elementos ".invocador"
let cantidad = 100;

for (let i = 0; i < cantidad; i++) {
    document.querySelectorAll(".invocador")[i].addEventListener("click", login);
}

function login() {
    document.querySelector("body").insertAdjacentHTML("beforeend", `
    <div class="cajaCortina">
        <div class="cortina">
            <div class="principal2">
                <form method="post" action="">
                    <h1 class="titulo">Identifícate</h1>
                    <br>
                    <div class="wave-group">
                        <input required="true" type="email" name="correo" class="input">
                        <span class="bar"></span>
                        <label class="label">
                            <span class="label-char" style="--index: 0">C</span>
                            <span class="label-char" style="--index: 1">o</span>
                            <span class="label-char" style="--index: 2">r</span>
                            <span class="label-char" style="--index: 3">r</span>
                            <span class="label-char" style="--index: 4">e</span>
                            <span class="label-char" style="--index: 5">o</span>
                        </label>
                    </div>
                    <br>
                    <div class="wave-group">
                        <input required="true" type="password" name="contra" id="password" class="input">
                        <span class="bar"></span>
                        <label class="label">
                            <span class="label-char" style="--index: 0">C</span>
                            <span class="label-char" style="--index: 1">o</span>
                            <span class="label-char" style="--index: 2">n</span>
                            <span class="label-char" style="--index: 3">t</span>
                            <span class="label-char" style="--index: 4">r</span>
                            <span class="label-char" style="--index: 5">a</span>
                            <span class="label-char" style="--index: 6">s</span>
                            <span class="label-char" style="--index: 7">e</span>
                            <span class="label-char" style="--index: 8">ñ</span>
                            <span class="label-char" style="--index: 9">a</span>
                        </label>
                    </div>
                    <br>
                    <div class="mostrarContrasena">
                        <input type="checkbox" id="checkboxMostrarContrasena"> Mostrar contraseña
                    </div>
                    <br><br>
                    <input type="submit" class="registro" name="Registrar" value="Entrar">
                    <br><br>
                    <a href="Registro.php" class="volver"> Registro </a>
                    <br><br>
                    <a href="RecContra.php" class="volver"> Recuperar Contraseña </a>
                    <br><br>
                    <a href="MainPage.php" class="volver"> Volver </a>
                </form>
            </div>
        </div>
    </div>`);

    // Agregar evento al checkbox para mostrar/ocultar la contraseña
    const checkboxMostrarContrasena = document.querySelector("#checkboxMostrarContrasena");
    const passwordInput = document.querySelector("#password");

    checkboxMostrarContrasena.addEventListener("change", function () {
        if (checkboxMostrarContrasena.checked) {
            passwordInput.type = "text"; // Cambia el tipo de campo a 'text'
        } else {
            passwordInput.type = "password"; // Cambia el tipo de campo a 'password'
        }
    });

    // Este es el manejador para cerrar sesión (si lo tienes)
    document.querySelector(".desinvocador").addEventListener("click", cerrar_sesion);
}
