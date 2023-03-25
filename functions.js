
function getBookData(id){

    let name = document.getElementById(`name-${id}`).innerText;
    let author = document.getElementById(`author-${id}`).innerText;
    let description = document.getElementById(`description-${id}`).innerText;
    let availability = document.getElementById(`availability-${id}`).innerText;    

    return {
        "name" : name,
        "author" : author,
        "description" : description,
        "availability" : availability
    };

}

function fillModal(id){

    let data = getBookData(id);
    let editModal = document.getElementById('edit-book-form');

    editModal.innerHTML =
    `
    <form class="custom-form" action="edit_book.php" method="post">
        <h4 class="text-center custom-text-blue">Edit book</h4>
        <input type="hidden" name="id" value="${id}">
        <div class="mt-2">
            <input type="text" name="name" id="edit-name" placeholder="Enter book name" required class="form-control" value="${data.name}">
        </div>
        <div class="mt-2">
            <textarea name="description" id="edit-description" cols="30" rows="5" placeholder="Enter book description" required class="form-control">${data.description}</textarea>
        </div>
        
        <p class="text-secondary mt-2">Editing author is not allowed! Recreate book in case of mistake.</p>
        <div class="mt-2 text-center">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </form>
    `;

}