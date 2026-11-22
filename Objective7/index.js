$(document).ready(function () {

    let $table = $(".projectMembersTable");
    let $body = $table.children("tbody").first(); 
    let keys = ["name", "position", "role"];       // one key per column
    let labels = ["Member", "Position", "Role"];

    let members = [];                              
    let sortKey = null;                            
    let sortDir = 1;                             
    let filters = { name: "", position: "", role: "" };

    // ---------- PART 1: read the existing HTML table into an array ----------
    function readMembers() {
        let result = [];
        let spans = [null, null, null]; 

        $table.find("tr").slice(1).each(function () {   
            let $cells = $(this).children("td");
            let values = [];
            let c = 0;

            for (let col = 0; col < 3; col++) {
                if (spans[col] && spans[col].left > 0) {
                    values[col] = spans[col].text;       
                    spans[col].left--;
                } else {
                    let $cell = $cells.eq(c++);          
                    values[col] = $cell.text().trim();
                    let rs = parseInt($cell.attr("rowspan")) || 1;
                    spans[col] = { text: values[col], left: rs - 1 };
                }
            }
            result.push({ name: values[0], position: values[1], role: values[2] });
        });
        return result;
    }

    // ---------- PART 2: helpers ----------
    function same(a, b) {
        return a.trim().toLowerCase() === b.trim().toLowerCase();
    }

    // apply the search filters, then the sort, and return the list to display
    function getVisible() {
        let list = members.filter(function (m) {
            return keys.every(function (k) {
                return m[k].toLowerCase().indexOf(filters[k]) > -1;
            });
        });

        if (sortKey) {
            list.sort(function (a, b) {
                return a[sortKey].localeCompare(b[sortKey], undefined, { sensitivity: "base" }) * sortDir;
            });
        }
        return list;
    }

    // ---------- PART 3: rebuild the table (merging identical neighbours) ----------
    function render() {
        let list = getVisible();

        $table.find("tr").slice(1).remove();   // remove all rows except the header

        let $positionLeader = null;
        let $roleLeader = null;

        $.each(list, function (i, m) {
            let prev = list[i - 1];
            let $row = $("<tr></tr>");

            $row.append($("<td></td>").text(m.name));

            if (prev && same(prev.position, m.position)) {
                $positionLeader.attr("rowspan", (parseInt($positionLeader.attr("rowspan")) || 1) + 1);
            } else {
                $positionLeader = $("<td></td>").text(m.position);
                $row.append($positionLeader);
            }

            if (prev && same(prev.role, m.role)) {
                $roleLeader.attr("rowspan", (parseInt($roleLeader.attr("rowspan")) || 1) + 1);
            } else {
                $roleLeader = $("<td></td>").text(m.role);
                $row.append($roleLeader);
            }

            $body.append($row);
        });

        updateHeaders();
    }

    // show an arrow in the header of the sorted column
    function updateHeaders() {
        $table.find("th").each(function (i) {
            let arrow = "";
            if (keys[i] === sortKey) arrow = sortDir === 1 ? " ▲" : " ▼";
            $(this).text(labels[i] + arrow);
        });
    }

    // ---------- PART 4: add a member  ----------
    $("#addLineButton").click(function () {
        let n = $("#nameMember").val().trim();
        let p = $("#position").val().trim();
        let r = $("#role").val().trim();
        if (!n || !p || !r) return;

        members.push({ name: n, position: p, role: r });
        render();

        $("#nameMember, #position, #role").val("");   // clear the 3 inputs
    });

    // ---------- PART 5: sorting (click on a header) ----------
    $table.on("click", "th", function () {
        let key = keys[$(this).index()];   // which column was clicked

        if (sortKey === key) {
            sortDir = -sortDir;            // same column: reverse the order
        } else {
            sortKey = key;                 // new column: start ascending
            sortDir = 1;
        }
        render();
    });
    $table.find("th").css("cursor", "pointer");

    // ---------- PART 6: search (one input per column) ----------
    let $search = $("<div class='searchBar'></div>");
    $.each(keys, function (i, key) {
        $("<input type='text'>")
            .attr("placeholder", "Search " + labels[i].toLowerCase() + "...")
            .data("key", key)
            .appendTo($search);
    });
    $table.before($search);

    $search.on("input", "input", function () {
        filters[$(this).data("key")] = $(this).val().trim().toLowerCase();
        render();
    });

    // ---------- START ----------
    members = readMembers();
    render();
});