class Todo {
    constructor() {
        this.tasks = JSON.parse(localStorage.getItem("todo-tasks") || "[]");
        this.term = "";
        this.list = document.getElementById("tasks");
        this.message = document.getElementById("message");
        this.draw();
    }

    validate(text, date) {
        if (text.trim().length < 3 || text.trim().length > 255) {
            return "Zadanie musi mieć od 3 do 255 znaków.";
        }
        if (date && !(new Date(date) > new Date())) {
            return "Termin musi być w przyszłości albo pusty.";
        }
        return "";
    }

    add(text, date = "") {
        this.message.textContent = this.validate(text, date);
        if (this.message.textContent) return false;
        this.tasks.push({ text: text.trim(), date });
        this.save();
        return true;
    }

    remove(index) {
        this.tasks.splice(index, 1);
        this.save();
    }

    edit(index, text, date) {
        this.message.textContent = this.validate(text, date);
        if (!this.message.textContent) {
            this.tasks[index] = { text: text.trim(), date };
            this.save();
        } else {
            this.draw();
        }
    }

    save() {
        localStorage.setItem("todo-tasks", JSON.stringify(this.tasks));
        this.draw();
    }

    getFilteredTasks() {
        return this.tasks.filter(task => this.term.length < 2 ||
            task.text.toLowerCase().includes(this.term.toLowerCase()));
    }

    highlight(element, text) {
        const term = this.term.toLowerCase();
        let start = 0;
        let match = term.length >= 2 ? text.toLowerCase().indexOf(term) : -1;
        while (match !== -1) {
            element.append(document.createTextNode(text.slice(start, match)));
            const mark = document.createElement("mark");
            mark.textContent = text.slice(match, match + term.length);
            element.append(mark);
            start = match + term.length;
            match = text.toLowerCase().indexOf(term, start);
        }
        element.append(document.createTextNode(text.slice(start)));
    }

    draw() {
        this.list.replaceChildren();
        for (const task of this.getFilteredTasks()) {
            const row = document.createElement("div");
            row.className = "task";
            row.dataset.index = this.tasks.indexOf(task);
            const content = document.createElement("span");
            content.className = "task-content";
            this.highlight(content, task.text);
            if (task.date) {
                const time = document.createElement("time");
                time.textContent = " — " + new Date(task.date).toLocaleString("pl-PL");
                content.append(time);
            }
            const button = document.createElement("button");
            button.textContent = "Usuń";
            row.append(content, button);
            this.list.append(row);
        }
    }

    startEditing(index) {
        const editor = document.createElement("span");
        editor.className = "task-editor";
        const text = document.createElement("input");
        text.type = "text";
        text.value = this.tasks[index].text;
        text.maxLength = 255;
        const date = document.createElement("input");
        date.type = "datetime-local";
        date.value = this.tasks[index].date;
        editor.append(text, date);
        this.list.querySelector('[data-index="' + index + '"] .task-content').replaceWith(editor);
        this.editor = { element: editor, index, text, date };
        text.focus();
    }

    finishEditing() {
        if (!this.editor) return;
        const { index, text, date } = this.editor;
        this.editor = null;
        this.edit(index, text.value, date.value);
    }
}

document.todo = new Todo();

// Kliknięcie poza edytorem zapisuje zmiany, a kliknięcie w zadanie otwiera edycję.
document.addEventListener("click", event => {
    const todo = document.todo;
    if (todo.editor && todo.editor.element.contains(event.target)) return;
    const row = event.target.closest(".task");
    const index = row ? Number(row.dataset.index) : null;
    const remove = event.target.tagName === "BUTTON" && row;
    todo.finishEditing();
    if (remove) todo.remove(index);
    else if (row) todo.startEditing(index);
});

document.getElementById("add-task").addEventListener("submit", event => {
    event.preventDefault();
    document.todo.finishEditing();
    if (document.todo.add(document.getElementById("task-text").value,
        document.getElementById("task-date").value)) {
        event.target.reset();
    }
});

document.getElementById("search").addEventListener("input", event => {
    document.todo.finishEditing();
    document.todo.term = event.target.value;
    document.todo.draw();
});
