new Vue({
  template:
    `<div class="admin">
      <div class="top">
        <img class="icon" src="../src/logo_w.png"/>
        <button @click="logout" type="button">Logout</button>
      </div>
      <div class="topic">
        <h2>sFTP File Download Log</h2>
      </div>
      <div class="search_box">
        <table>
          <tbody>
            <tr>
              <!-- Download Start Calendar Search -->
              <th>Download Date</th>
              <td class="date_search">
                <input type="date" class="date_box" v-model="sDate" required>
                <span class="dateCk">-</span>
                <input type="date" class="date_box" v-model="today" :max="getMaxDate" required>
              </td>
              <!-- Search for Download not finish -->
              <!-- <td class="download_not_finish">
                <input type="checkbox" v-model="downloadNotFinished" @change="searchDownloadNotFinished"/>
                <label for="checkbox">Download not finished</label>
              </td> -->
              <!-- User Input Search -->
              <td class="search_type">
                <select v-model="searchType">
                  <option v-for="type in searchTypes">{{ type }}</option>
                </select>
                <input type="text" v-model="searchField" minlength="3" pattern=".{3,}" @keyup.enter="search">
                <button @click="search" type="button" id="searchButton">Search</button>
                <button @click="exportToCSV" id="exportButton">Export</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- TABLE -->
      <div class="content">
        <div class="scroll">
          <!-- Filtered row summary  TO DO -->
          <div class="table_summary">Total results: {{ totalRows }}</div>
          <table>
            <thead>
              <tr>
                <th v-for="head in tableHeads">{{ head }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in data">
                <td>{{ row.user_account }}</td>
                <td>{{ row.order_id }}</td>
                <td>{{ row.sample_name }}</td>
                <td>{{ row.file_name }}</td>
                <td>{{ row.file_type }}</td>
                <td>{{ row.start_timestamp }}</td>
                <!-- <td>{{ row.finish_timestamp }}</td> -->
              </tr> 
            </tbody>
          </table>
        </div>
      </div>
      <div class="page">
        <button @click="previousPage" :disabled="currentPage === 1">Previous</button>
        <span>Page {{ currentPage }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
      </div>
    </div>
    `,
  el: "#table",
  data() {
      return {
        date: new Date(),
        today: new Date().toISOString().slice(0,10),
        sDate: '',
        searchTypes: ['Order', 'Sample', 'File', 'User'],
        searchType: 'Order',
        searchField: '',
        tableHeads: ['User', 'Order', 'Sample', 'File', 'File Type', 'Download Date'], //, 'Download Finish'],
        currentPage: 1,
        totalPages: null,
        recordsPerPage: 28,
        data: [],
        exportData: [],
        totalRows: null,
        // downloadNotFinished: false,
      };
  },
  created(){
      // Getting start date
      var startDate = new Date(this.date.getFullYear(), this.date.getMonth(), this.date.getDate()-92);
      this.sDate = startDate.toISOString().slice(0,10);
      this.data
      // fetching the first log page
      this.fetchData(this.currentPage, this.sDate, this.today, this.searchType, this.searchField);
    },
  computed:
    {
      getMaxDate() {
        var maxDate = new Date(this.date.getFullYear(), this.date.getMonth(), this.date.getDate());
        return maxDate.toISOString().slice(0,10)
      },
      searchValid() {
        return this.searchField.length>=3 || this.searchField.length==0
      },
    },
  methods:
    {
      async fetchData(page, start, end, type, field) {
        if (start > end && end != '') {
          alert('Start Date cannot exceed End Date')
          pass
        } 
        if (start == '') {
          alert('You must specify Start Date')
          pass
        }
        // Getting total number of rows for the filter
        try {
          totalRows = await (await fetch(`getlog.php?page=0&start=${start}&end=${end}&type=${type}&field=${field}`)).json();
          this.totalRows = totalRows[0].count;
        } catch (error) {
          // alert('Error')
          console.error(error);
        }
        // Getting rows for filter
        try {
          logData = await (await fetch(`getlog.php?page=${page}&start=${start}&end=${end}&type=${type}&field=${field}`)).json();
          this.data = logData;
          this.currentPage = page;
          this.searchField = field;
          this.searchType = type;
          this.sDate = start;
          this.today = end;
          this.totalPages = Math.ceil(this.totalRows / this.recordsPerPage);
          // if (this.downloadNotFinished) {
          //   this.downloadNotFinished = false
          // }
   
        } catch (error) {
          alert('Error')
          console.error(error);
        }
      },
      logout() {
        if(call("account/logout")){
          location.href=urlroot;
        }
      },
      search() {
        if (this.searchValid) {
          this.fetchData(1, this.sDate, this.today, this.searchType, this.searchField);
        } else {
          alert('Search more than 3 characters')
        }
      },
      previousPage() {
        if (this.currentPage > 1) {
          this.fetchData(this.currentPage -1, this.sDate, this.today, this.searchType, this.searchField);

        }
      },
      nextPage() {
        if (this.currentPage < this.totalPages) {
          this.fetchData(this.currentPage + 1, this.sDate, this.today, this.searchType, this.searchField);
        }
      },
      // searchDownloadNotFinished() {
      //   if (this.downloadNotFinished) {
      //     this.fetchData(this.currentPage, this.sDate, '', this.searchType, this.searchField)
      //   } else {
      //     alert(downloadNotFinished)
      //     this.fetchData(this.currentPage, this.sDate, this.today, this.searchType, this.searchField)
      //   } 
      // },
      async exportToCSV() {
        // Getting all filtered data
        try {
          this.exportData = await (await fetch(`getlog.php?page=0&start=${this.sDate}&end=${this.today}&type=${this.searchType}&field=${this.searchField}&export=true`)).json();
        } catch (error) {
          // alert('Error')
          console.error(error);
        }
        
        csvData = this.convertToCSV(this.exportData)
        blob = new Blob([csvData], {type: "text/csv"});
        url = URL.createObjectURL(blob);
        link = document.createElement("a");
        link.setAttribute("href", url);
        link.setAttribute("download", `Exported FTP log Data ${this.date}.csv`);
        link.click();
      },
      convertToCSV(jsonData) {
        keys = Object.keys(jsonData[0]);
        header = keys.join(",");
        body = jsonData
          .map(row => {
            return keys
              .map(key => {
                cell = row[key];
                return cell.includes(",") ? `"${cell}"` : cell;
              })
              .join(",");
          })
          .join("\n");
        return `${header}\n${body}`;
      },

    },
});
  