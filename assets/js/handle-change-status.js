const checkbox = document.querySelectorAll(".handle-change-status");

async function handleChangeStatus(form) {
    try {
        await fetch(form.action, {
            method: form.method
        });
    } catch (err) {
        console.error(err)
    }
}

for (const c of checkbox) {
    c.addEventListener('click', (e) => {
        const form = e.target.parentNode;
        handleChangeStatus(form);
    });
}


