function create_forum_post() {
    var cPost_title = document.forms.create_post.post_title.value,
        cPost_desc = document.forms.create_post.post_desc.value,
        cPost_title_err = document.getElementById('cPost_title_err'),
        cPost_desc_err = document.getElementById('cPost_desc_err');
    
        cPost_title_err.innerHTML = '';
        cPost_desc_err.innerHTML = '';


    if(cPost_title.trim() === '') {
        cPost_title_err.innerHTML = 'Title is required';
        document.forms.create_post.post_title.focus();
        return false;
    } else {
        cPost_title_err.innerHTML = '';
    }
    
    if(cPost_desc.trim() === '') {
        cPost_desc_err.innerHTML = 'Description is required';
        document.forms.create_post.post_desc.focus();
        return false;
    } else {
        cPost_desc_err.innerHTML = '';
    }
}