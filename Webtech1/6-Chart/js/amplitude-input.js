const template = document.createElement('template');
template.innerHTML =
    `<style>
        h4 {
        margin-top: 10px;
        margin-bottom: 0;
        }
        #input-container, #slider-container {
        height: 22px;
        }
        #slider-container {
            position: relative;
            height: 22px;
            display: none;
            align-items: center;
        }
        #slider-value {
            -webkit-appearance: none;
            width: 120px;
            height: 7px;
            outline: none;
            border-radius: 3px;
            border: 2px solid grey;
            margin: 0;
        }
        /*chrome*/
        #slider-value::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 22px;
            height: 22px;
            cursor: pointer;
            z-index: 3;
            position: relative;
        }
        /*firefox*/
        .slider::-moz-range-thumb {
          width: 22px;
          height: 22px;
          cursor: pointer;
          position: relative;
          z-index: 3;
        }
        
        .selector {
            height: 18px;
            width: 18px;
            position: absolute;
            background-color: lightgrey;
            border: 2px solid grey;
            border-radius: 3px;
            z-index: 2;
            top: 0;
            left: 0;
            text-align: center;
        }
     </style>
     <div>
     <h4>Amplitúda</h4>
     <label for="slider">Slider</label>
     <input type="radio" name="type" id="slider"><br>
     <label for="input">Input</label>
     <input type="radio" name="type" id="input" checked>
     <div id="input-container">
        <input type="number" id="input-value" min="1" max="10" value="1">
     </div>
     <div id="slider-container">
        <input type="range" id="slider-value" min="1" max="10" value="1">
        <div class="selector">1</div>
     </div>
     </div>`;

class AmplitudeInput extends HTMLElement{

    constructor() {
        super();
        this.attachShadow({mode: "open"});
        this.shadowRoot.appendChild(template.content.cloneNode(true));

        const inputR = this.shadowRoot.querySelector("#input");
        inputR.addEventListener('click', () => {
            this.shadowRoot.querySelector("#input-container").style.display = "block";
            this.shadowRoot.querySelector("#slider-container").style.display = "none";
        })

        const sliderR = this.shadowRoot.querySelector("#slider");
        sliderR.addEventListener('click', () => {
            this.shadowRoot.querySelector("#input-container").style.display = "none";
            this.shadowRoot.querySelector("#slider-container").style.display = "flex";
        })

        const input = this.shadowRoot.querySelector("#input-value");
        const slider = this.shadowRoot.querySelector("#slider-value");
        const selector = this.shadowRoot.querySelector(".selector");

        const moveSelector = () => {
            selector.style.left = ((slider.value - slider.min) / (slider.max - slider.min)) * 102 + "px";
            selector.innerHTML = slider.value;
        }

        input.addEventListener('change', () => {
            if(input.validity.valid){
                slider.value = input.value;
                moveSelector();
                this.setAttribute('value', input.value);
            }else{
                input.value = slider.value;
            }
        })

        slider.addEventListener('change', () => {
            input.value = slider.value;
            this.setAttribute('value', input.value);
        })

        slider.addEventListener('input', moveSelector);
    }

}

window.customElements.define("amplitude-input", AmplitudeInput);