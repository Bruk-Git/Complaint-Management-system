const form = document.querySelector("form"),
        nextbtn = form.querySelector(".nextbtn"),
        backbtn = form.querySelector(".backbtn"),
        allInput = form.querySelectorAll(".form-first input");


nextBtn.addEventListener("click", ()=>{
    allInput.forEach(input =>{
        if (input.value != "") {
            form.classList.add('seaActive');
        } else {
            form.classList.remove('seaActive');
            alert("empty")
        }
    })
})
backbtn.addEventListener("click", ()=> form.classList.remove('seaActive'))