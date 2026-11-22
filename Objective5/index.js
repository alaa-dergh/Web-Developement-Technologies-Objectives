
let nameMember = document.getElementById("nameMember");
let position = document.getElementById("position");
let role = document.getElementById("role");
let button = document.getElementById("addLineButton");
let table = document.querySelector(".projectMembersTable");

// PART 1: read the existing HTML table into an array
function readMembers() {
    let result = [];
    let spans = [null, null, null]; // remembers the active rowspan of each column

    for (let i = 1; i < table.rows.length; i++) {
        let cells = table.rows[i].cells;
        let values = [];
        let c = 0; // index of the next real cell in this row

        for (let col = 0; col < 3; col++) {
            if (spans[col] && spans[col].left > 0) {
                values[col] = spans[col].text;   // covered by a cell above
                spans[col].left--;
            } else {
                let cell = cells[c++];           // this row owns the cell
                values[col] = cell.textContent.trim();
                spans[col] = { text: values[col], left: cell.rowSpan - 1 };
            }
        }
        result.push({ name: values[0], position: values[1], role: values[2] });
    }
    return result;
}

// PART 2: comparison helper
function same(a, b) {
    return a.trim().toLowerCase() === b.trim().toLowerCase();
}

// PART 3: rebuild the table from the array, merging identical neighbours
function render() {
    while (table.rows.length > 1) table.deleteRow(1); // keep the header

    let positionLeader = null;
    let roleLeader = null;

    members.forEach(function (m, i) {
        let prev = members[i - 1];
        let row = table.insertRow(-1);

        row.insertCell().textContent = m.name;

        if (prev && same(prev.position, m.position)) {
            positionLeader.rowSpan++;
        } else {
            positionLeader = row.insertCell();
            positionLeader.textContent = m.position;
        }

        if (prev && same(prev.role, m.role)) {
            roleLeader.rowSpan++;
        } else {
            roleLeader = row.insertCell();
            roleLeader.textContent = m.role;
        }
    });
}

// PART 4: add a member from the form
function addLine() {
    let n = nameMember.value.trim();
    let p = position.value.trim();
    let r = role.value.trim();
    if (!n || !p || !r) return;

    members.push({ name: n, position: p, role: r });
    render();

    nameMember.value = "";
    position.value = "";
    role.value = "";
}

// START
let members = readMembers();   // data now comes from the HTML
render();



/*let nameMember = document.getElementById("nameMember");
let position = document.getElementById("position");
let role = document.getElementById("role"); 
let button = document.getElementById("addLineButton");

function addLine() {
    if (nameMember.value && position.value && role.value) {
        let table = document.querySelector(".projectMembersTable");
        let newRow = table.insertRow(-1);
        let nameCell = newRow.insertCell(0);
        let positionCell = newRow.insertCell(1);
        let roleCell = newRow.insertCell(2);
        nameCell.innerHTML = nameMember.value;
        positionCell.innerHTML = position.value;
        roleCell.innerHTML = role.value;
    }
}
*/
/*let nameMember = document.getElementById("nameMember");
let position = document.getElementById("position");
let role = document.getElementById("role");
let button = document.getElementById("addLineButton");

function addLine() {
    let nameValue = nameMember.value.trim();
    let positionValue = position.value.trim();
    let roleValue = role.value.trim();

    if (!nameValue || !positionValue || !roleValue) return;

    let table = document.querySelector(".projectMembersTable");

    // 1. Find the "group leader" row: walk up from the last row
    //    until we find a row that owns a position cell (3 cells).
    let index = table.rows.length - 1;
    while (index > 0 && table.rows[index].cells.length < 3) {
        index--;
    }

    // index 0 is the header row -> no merge possible
    let leaderCell = null;
    if (index > 0) {
        leaderCell = table.rows[index].cells[1];
    }

    // 2. Compare positions (case-insensitive)
    let samePosition =
        leaderCell !== null &&
        leaderCell.textContent.trim().toLowerCase() === positionValue.toLowerCase();

    // 3. Insert the new row
    let newRow = table.insertRow(-1);
    let nameCell = newRow.insertCell(0);
    nameCell.textContent = nameValue;

    if (samePosition) {
        // Extend the existing position cell, no position cell in the new row
        leaderCell.rowSpan = leaderCell.rowSpan + 1;
        let roleCell = newRow.insertCell(1);
        roleCell.textContent = roleValue;
    } else {
        let positionCell = newRow.insertCell(1);
        let roleCell = newRow.insertCell(2);
        positionCell.textContent = positionValue;
        roleCell.textContent = roleValue;
    }


}
    */
