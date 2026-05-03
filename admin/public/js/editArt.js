const gallery = document.getElementById('gallery');
const input = document.getElementById('artImages');
let existingImages = JSON.parse(gallery.dataset.existingImages || '[]');
let currentFiles = [];

function renderImage(imgObj, index, isExisting=false){

    const div = document.createElement('div');
    div.className = 'gallery-item';
    div.dataset.index = index;
    div.dataset.existing = isExisting ? '1' : '0';

    if(!isExisting){
        div.dataset.fileIndex = index.replace('n-','');
    }

    const src = isExisting ? `../public/${imgObj.image}` : imgObj.image;

    div.innerHTML = `
        <img src="${src}" alt="">
        <span class="item-number">#</span>
        <button type="button" class="delete-btn">X</button>
    `;

    gallery.appendChild(div);

    div.querySelector('.delete-btn').onclick = () => {
        if(isExisting){
            imgObj._delete = true;
        }else{
            const i = parseInt(index.replace('n-',''));
            currentFiles[i] = null;
        }

        div.remove();
        updateNumbers();
    };
    updateNumbers();
}

function updateNumbers() {
    Array.from(gallery.children).forEach((div, idx) => {
        const numberSpan = div.querySelector('.item-number');
        if (numberSpan) numberSpan.textContent = idx + 1;
        div.dataset.index = div.dataset.existing === '1' ? div.dataset.index : 'n-' + idx;
    });
}

existingImages.forEach(img=>{
    renderImage(img,'e-'+img.id,true);
});

input.addEventListener('change',()=>{

    Array.from(input.files).forEach(file=>{
        const idx = 'n-'+currentFiles.length;
        currentFiles.push(file);

        renderImage({
            image: URL.createObjectURL(file)
        },idx,false);
    });

    input.value='';
});

new Sortable(gallery,{
    animation:150,
    ghostClass:'sortable-ghost',
    onEnd: () => {
        const newFiles = [];
        Array.from(gallery.children).forEach(div => {
            if(div.dataset.existing === '0'){
                const fileIndex = parseInt(div.dataset.fileIndex);
                newFiles.push(currentFiles[fileIndex]);

                div.dataset.fileIndex = newFiles.length - 1;
                div.dataset.index = 'n-' + (newFiles.length - 1);
            }
        });

        currentFiles = newFiles;
        updateNumbers();
    }
});

const form = document.querySelector('form');

form.addEventListener('submit',()=>{

    const order = [];
    let newIndex = 0;

    Array.from(gallery.children).forEach(div => {
        if(div.dataset.existing === '1'){
            order.push(div.dataset.index);
        }else{
            order.push('n-' + newIndex);
            newIndex++;
        }
    });

    const orderInput = document.createElement('input');
    orderInput.type='hidden';
    orderInput.name='image_order';
    orderInput.value = order.join(',');
    form.appendChild(orderInput);

    const dt = new DataTransfer();
    currentFiles.forEach(f=>{
        if(f) dt.items.add(f);
    });
    input.files = dt.files;

    existingImages.forEach(img=>{
        if(img._delete){
            const delInput = document.createElement('input');
            delInput.type='hidden';
            delInput.name='delete_images[]';
            delInput.value = img.id;
            form.appendChild(delInput);
        }
    });

});
