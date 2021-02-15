 formEdit = document.querySelectorAll('.Edit');
 urlpath = "/bot/edit/"

for (let i = 0; i < formEdit.length; i++) {
    formEdit[i].addEventListener("click", function (e) {
        e.preventDefault();

        dataId = formEdit[i].getAttribute('data-id')

        fetch(urlpath+dataId, {
                    method: 'POST'

            }).then(function (reponse) {
            return reponse.text();
        }).then(function (datas) {
            html = datas;
            signaform = document.querySelector('#editform');
            signaform.innerHTML = html;

            editionForm = document.querySelector('form[name="edit_bot_content"]');
     
            editionForm.addEventListener("submit", function (e) {
                e.preventDefault();

                dataSend = new FormData(editionForm);

                    fetch(urlpath+dataId, {
                        method: 'POST',
                        body: dataSend

                    }).then(function (reponse) {
                    return reponse.text();
                }).then(function (datas) {
                    html = datas.message;

                    if (datas.code == 001) {
                    signaform.classList.add('d-none')
                    }
                    
                     setTimeout("document.location.reload(true)", 2000);
                })
            })

        })
    })
}
