let dataName = [];
let dataNickname = [];
let clickCount = 0;

const hiddenBtn = document.getElementById('tuikaBtn');

document.getElementById('tuikaBtn').addEventListener('click', function () {
    var name = document.getElementById('namae').value;
    var nickname = document.getElementById('nickname').value;

    if (name && nickname && clickCount < 3) {
        if (window.confirm("[" + name + "]さん[" + nickname + "]を登録します。よろしいですか？")) {
            window.alert("[" + name + "]さん[" + nickname + "]にて登録しました.");
            clickCount++;
            dataName.push(name);
            dataNickname.push(nickname);

            if (clickCount >= 3) {
                hiddenBtn.style.display = 'none';
            }

            updateTable();
        }
    }
});

function updateTable() {
    const table = document.createElement('table');

    for (let i = 0; i < clickCount; i++) {
        const row = document.createElement('tr');
        row.innerHTML = `
        <td>${dataName[i]}</td>
        <td>${dataNickname[i]}</td>
        <td><button class="deleteBtn" dataNumber="${i}">削除</button></td>
    `;

    table.appendChild(row);

    // 削除ボタンを押したときの処理
    const deleteBtn = row.querySelector('.deleteBtn');
    deleteBtn.addEventListener('click', function () {
        const number = this.getAttribute('dataNumber');
        dataName.splice(number, 1);
        dataNickname.splice(number, 1);
        clickCount--;
        hiddenBtn.style.display = 'block';
        updateTable();
    });
}

// 前の情報が残らないように処理
const tuikaMoto = document.getElementById('tuikaMoto');
tuikaMoto.innerHTML = '';

// 新しいテーブルを追加
tuikaMoto.appendChild(table);
}