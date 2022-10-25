<template>
  <div class="container">

    <form id="form-houses" class="p-3" v-on:submit="onTouch">

      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" aria-describedby="nameHelp"
               placeholder="Enter name">
      </div>
      <div class="form-group price-group">
        <div>
          <label for="price-min">Price Min</label>
          <input type="number" class="form-control" id="price-min" name="price_min" aria-describedby="nameHelp"
                 placeholder="Enter min price">
        </div>
        <div>
          <label for="price-max">Price Max</label>
          <input type="number" class="form-control" id="price-max" name="price_max" aria-describedby="nameHelp"
                 placeholder="Enter max price">
        </div>
      </div>
      <div class="form-group">
        <label for="bedrooms">Bedrooms</label>
        <input type="number" class="form-control" id="bedrooms" name="bedrooms" placeholder="Bedrooms">
      </div>
      <div class="form-group">
        <label for="bathrooms">Bathrooms</label>
        <input type="number" class="form-control" id="bathrooms" name="bathrooms" placeholder="bathrooms">
      </div>
      <div class="form-group">
        <label for="storeys">Storeys</label>
        <input type="number" class="form-control" id="storeys" name="storeys" placeholder="storeys">
      </div>
      <div class="form-group">
        <label for="garages">Garages</label>
        <input type="number" class="form-control" id="garages" name="garages" placeholder="garages">
      </div>
      <input type="hidden" name="_token" :value="csrf">

      <div class="form-bottom mt-3">
        <button type="submit" class="btn btn-primary">Submit</button>
        <img v-if="isLoading" src="images/Spinner.svg" alt="">
      </div>

    </form>


    <div class="container results" >
      <div class="no-results alert alert-danger" v-if="emptyResult">Results not found</div>
      <table class="table-results table" v-show="showTable">
        <thead>
          <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Bedrooms</th>
            <th>Bathrooms</th>
            <th>Storeys</th>
            <th>Garages</th>
          </tr>
        </thead>
        <tbody id="table-results-body">

        </tbody>
      </table>
    </div>

  </div>

</template>

<script>

export default {
  data: () => ({
    isLoading: false,
    isResult: false,
    emptyResult: false,
    showTable: false,
    csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
  }),
  methods: {
    onTouch: function (e) {
      e.preventDefault();

      this.isLoading = true;
      this.emptyResult = false;
      this.showTable = false;

      this.clearTable();

      const tableResults = document.querySelector('.table-results tbody')

      const form = document.querySelector('#form-houses');
      const data = {};
      data.name = form.querySelector("input[name='name']").value;
      data.priceMin = form.querySelector("input[name='price_min']").value;
      data.priceMax = form.querySelector("input[name='price_max']").value;
      data.bedrooms = form.querySelector("input[name='bedrooms']").value;
      data.bathrooms = form.querySelector("input[name='bathrooms']").value;
      data.storeys = form.querySelector("input[name='storeys']").value;
      data.garages = form.querySelector("input[name='garages']").value;

      const res = this.sendData(data, tableResults);

      res.then((response)=> {
        this.isLoading = false;
        if (response.data && response.data.length > 0) {
          response.data.forEach(function (row) {
            tableResults.insertAdjacentHTML('beforeend', `
            <tr>
              <td>` + row.name + `</td>
              <td>` + row.price + `</td>
              <td>` + row.bedrooms + `</td>
              <td>` + row.bathrooms + `</td>
              <td>` + row.storeys + `</td>
              <td>` + row.garages + `</td>
            </tr>
            `)
          })
          this.showTable = true;
        } else if (response.data && response.data.length === 0) {
          this.emptyResult = true;
        }

      })
    },
    sendData(data, tableResults) {
      return axios({
        url: 'api/get-data',
        method: "POST",
        data: data,
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      .then((response) => {
        return response
      })
      .catch(function (error) {
        console.log(error);
      });

    },
    clearTable() {
      const oldTbody = document.querySelector('.table-results tbody');
      const newTbody = document.createElement('tbody');
      oldTbody.parentNode.replaceChild(newTbody, oldTbody);
    }
  }
}
</script>

<style>
form input {
  display: block;
}

.form-group {
  margin-top: 5px;
}

.price-group {
  display: flex;
  justify-content: space-between;
}

.price-group > div {
  width: 49%;
}

.form-bottom {
  display: flex;
  align-items: center;
}

.form-bottom img {
  width: 50px;
  height: 50px;
}
</style>
