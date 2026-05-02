const gallery = document.getElementById('gallery');
const input = document.getElementById('artImages');
let currentFiles = []; 

input.addEventListener('change', () => {
    Array.from(input.files).forEach(file => {
        currentFiles.push(file);

        const div = document.createElement('div');
        div.className = 'gallery-item';
        div.fileRef = file;
        
        div.innerHTML = `
            <span class="item-number">${currentFiles.length}</span>
            <img src="${URL.createObjectURL(file)}" />
            <button type="button" class="delete-btn">X</button>
        `;
        gallery.appendChild(div);

        div.querySelector('.delete-btn').onclick = () => {
            currentFiles = currentFiles.filter(f => f !== file);
            div.remove();
            updateNumbers();
        };
    });

    input.value = '';
});

function updateNumbers() {
    Array.from(gallery.children).forEach((div, idx) => {
        const span = div.querySelector('.item-number');
        if(span) span.textContent = idx + 1;
    });
}

new Sortable(gallery, {
    animation: 150,
    ghostClass: 'sortable-ghost',
    onEnd: () => {
        currentFiles = Array.from(gallery.children).map(div => div.fileRef);
        updateNumbers();
    }
});

const form = document.querySelector('form');
form.addEventListener('submit', e => {
    const dt = new DataTransfer();
    currentFiles.forEach(file => dt.items.add(file));
    input.files = dt.files;
});
