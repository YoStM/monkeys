window.onload;

const BTN_UPDATE = document.getElementById("updatebtn");
const INPUT_PERSONAL_INFO = document.getElementById("PersonalInfo").getElementsByTagName("input");
const INPUT_PERSONAL_INFO_LENGTH = INPUT_PERSONAL_INFO.length;
BTN_UPDATE.addEventListener("click", enableInput);

function enableInput() {
    console.log("Qui a touché au bouton ?");

    if (BTN_UPDATE.innerHTML === "Modifier") {

        for (let i = 0; i < INPUT_PERSONAL_INFO_LENGTH; i++) {

            if (INPUT_PERSONAL_INFO[i].hasAttribute('disabled')) {
                INPUT_PERSONAL_INFO[i].removeAttribute('disabled');
            }

        }

        BTN_UPDATE.setAttribute('type', 'submit');
        BTN_UPDATE.innerHTML = "Valider les changements !";

    } else {

        for (let i = 0; i < INPUT_PERSONAL_INFO_LENGTH; i++) {

            INPUT_PERSONAL_INFO[i].setAttribute('disabled', true);

        }

        BTN_UPDATE.innerHTML = "Modifier";

    }


}