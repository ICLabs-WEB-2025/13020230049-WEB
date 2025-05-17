const darkModeToggle = document.getElementById('darkModeToggle');

let kondisional = 0;

darkModeToggle.addEventListener('click',function(e){
    kondisional +=1;
    if(kondisional %2 == 0){
        console.log("light mode");
        document.body.classList.remove("bg-dark")
        document.body.classList.remove("text-light")

        
    }
    else{
        console.log("dark mode")
        document.body.classList.add("bg-dark");
        document.body.classList.add("text-light");
    }
    console.log(kondisional);
})
