
     img.onclick = () => file.click()
file.addEventListener('change', function() {
     /* to get file  */
     let f = file.files[0]
     /* use url object for to get file url */
     img.src = URL.createObjectURL(f)
     console.log(f)
})