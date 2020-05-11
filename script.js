recordCount = 1
function newRowCreate(data){
    const tr = document.createElement("tr")
    let td = ""

    // td = document.createElement("th")
    // td.innerHTML = recordCount++
    // td.scope = "row"
    // td.setAttribute("class","col-3")
    // tr.appendChild(td)
    td = document.createElement("th")
    td.innerHTML = data.title.toString().toUpperCase()
    // td.setAttribute("class","col-2")
    tr.appendChild(td)
    td = document.createElement("td")
    td.innerHTML = data.total_cases
    // td.setAttribute("class","col-2")
    tr.appendChild(td)
    td = document.createElement("td")
    td.innerHTML = data.total_recovered
    // td.setAttribute("class","col-2")
    tr.appendChild(td)
    td = document.createElement("td")
    td.innerHTML = data.total_deaths
    // td.setAttribute("class","col-2")
    tr.appendChild(td)
    td = document.createElement("td")
    td.innerHTML = data.total_new_cases_today
    // td.setAttribute("class","col-2")
    tr.appendChild(td)
    td = document.createElement("td")
    td.innerHTML = data.total_new_deaths_today
    // td.setAttribute("class","col-2")
    tr.appendChild(td)

    return tr
}

const host = window.location.hostname === "localhost" ? "http://localhost/developerswork/coronavirus/" : "https://coronavirus.developerswork.online/"

function fetchData(){
    const tbody = document.getElementById("dataholder")
    tbody.innerHTML = ""
    return fetch(host + "api/v1/global")
    .then(res => {
        // console.log(res.data())
        return res.json()
    }).then(json => {
        // console.log(json)
        const tr = newRowCreate(json.data[0])
        tbody.appendChild(tr)
        // console.log(data)
        return fetch(host + "api/v1/all_countries")
    }).then(res => res.json())
    .then(json => {
        // console.log(json)
        const promises = json.data.map(country => {
            // getLiveData(country.alpha2Code,tbody)
            const tr = newRowCreate(country)
            tbody.appendChild(tr)
        })
        return Promise.all(promises)
    }).then(() => {
        document.getElementById("loading").setAttribute("class","")
    }).catch(err => {
        console.log(err)
    })
}