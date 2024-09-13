<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.5/css/dataTables.dataTables.css" />
  </head>
  <body>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <form action="#" id="form-product">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel"></h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="name" class="col-form-label">Name:</label>
                <input type="hidden" class="form-control" name="id" id="id">
                <input type="text" class="form-control" name="name" id="name">
                <div class="alert alert-danger d-none" role="alert" id="alert-name"></div>
              </div>
              <div class="mb-3">
                <label for="slug" class="col-form-label">Slug:</label>
                <input type="text" class="form-control" name="slug" id="slug">
                <div class="alert alert-danger d-none" role="alert" id="alert-slug"></div>
              </div>
              <div class="mb-3">
                <label for="description" class="col-form-label">Description:</label>
                <textarea class="form-control" name="description" id="description"></textarea>
                <div class="alert alert-danger d-none" role="alert" id="alert-description"></div>
              </div>
              <div class="mb-3">
                <label for="price" class="col-form-label">Price:</label>
                <input type="number" class="form-control" name="price" id="price">
                <div class="alert alert-danger d-none" role="alert" id="alert-price"></div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="cancel-button">Batal</button>
              <button type="submit" class="btn btn-primary" id="save-button">Simpan</button>
              <button type="submit" class="btn btn-primary" id="update-button">Update</button>
            </div>
          </div>
        </div>
      </form>
    </div>
    <div class="container mt-5">
      <div class="card">
        <div class="card-header">
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="modal-button">
            Tambah Data
          </button>
        </div>
        <div class="card-body">
          <table class="table" id="productTable">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Slug</th>
                <th scope="col">Description</th>
                <th scope="col">Price</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody id="data-product">
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.1.5/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      $(document).ready(function(){
        productTable();
      })

      $("#modal-button").click(function(){
        $("#staticBackdropLabel").text("Simpan data")
        $("#save-button").removeClass("d-none")
        $("#update-button").addClass("d-none")
      })

      $(document).on("click","#save-button", function(e){
        e.preventDefault()
        let name        = $("input[name='name']").val()
        let slug        = $("input[name='slug']").val()
        let description = $("textarea[name='description']").val()
        let price       = $("input[name='price']").val()

        $.ajax({
          type: "POST",
          url: "/product",
          headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          data: {
            "name": name,
            "slug": slug,
            "description": description,
            "price": price 
          },
          success: function(response){
            console.log(response)
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: response,
              showConfirmButton: false,
              timer: 1500
            });
            let html = `
              <tr>
                <td>`+ response.number +`</td>
                <td>`+ response.data.name +`</td>
                <td>`+ response.data.slug +`</td>
                <td>`+ response.data.description +`</td>
                <td>`+ response.data.price +`</td>
                <td>
                  <button class="btn btn-md btn-warning" data-id="`+ response.data.id +`">Edit</button>
                  <button class="btn btn-md btn-danger" data-id="`+ response.data.id +`">Hapus</button>
                </td>
              </tr>
            `
            $("#data-product").append(html)
            $("input[name='name']").val(null)
            $("input[name='slug']").val(null)
            $("textarea[name='description']").val(null)
            $("input[name='price']").val(null)
            $(".modal").modal('hide')
          },
          error: function(xhr, status, error){
            console.log(xhr.responseJSON.slug)

            if(xhr.responseJSON.name){
              $("#alert-name").removeClass("d-none")
              $("#alert-name").text(xhr.responseJSON.name[0])
            }

            if(xhr.responseJSON.slug){
              $("#alert-slug").removeClass("d-none")
              $("#alert-slug").text(xhr.responseJSON.slug[0])
            }

            if(xhr.responseJSON.description){
              $("#alert-description").removeClass("d-none")
              $("#alert-description").text(xhr.responseJSON.description[0])
            }
            
            if(xhr.responseJSON.price){
              $("#alert-price").removeClass("d-none")
              $("#alert-price").text(xhr.responseJSON.price[0])
            }
          }
        })
      })

      $(document).on("click","#cancel-button", function(){
        $("input[name='name']").val(null)
        $("input[name='slug']").val(null)
        $("textarea[name='description']").val(null)
        $("input[name='price']").val(null)
      })

      function productTable(){
        $('#productTable').DataTable({
          processing: true,
          serverSide: true,
          ajax: 'product/render_serverside',
          columns: [
            {
              data: "DT_RowIndex",
              name: "DT_RowIndex",
            },
            {
              data: "name",
              name: "name",
            },
            {
              data: "slug",
              name: "slug",
            },
            {
              data: "description",
              name: "description"
            },
            {
              data: "price",
              name: "price",
            },
            {
              data: "Action",
              name: "Action",
            }
          ],
          rowId: function(data) {
              return 'row-' + data.id;
          }
        });
      }

      $(document).on("click", ".btn-danger", function(){
        let id = $(this).data("id")
        let row = $(this).parent().parent().parent()
        $.ajax({
          type: "DELETE",
          url: "/product/"+id,
          headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          success: function(success){
            Swal.fire({
              position: "top-end",
              icon: "success",
              title: "Data berhasil dihapus",
              showConfirmButton: false,
              timer: 1500
            });
            // $(this).parent().parent().parent().remove()
            row.remove()
          },
          error: function(xhr, status, error){
            console.log(xhr)
          }
        })
      })

      $(document).on("click", ".btn-warning", function(){
        $("#save-button").addClass("d-none")
        $("#update-button").removeClass("d-none")
        let id = $(this).data("id")
        $.ajax({
          type: "GET",
          url: "/product/" + id,
          success: function(result){
            console.log(result)
            $(".modal-title").text("Edit Data")
            $("input[name='id']").val(result.data.id)
            $("input[name='name']").val(result.data.name)
            $("input[name='slug']").val(result.data.slug)
            $("textarea[name='description']").val(result.data.description)
            $("input[name='price']").val(result.data.price)
            $(".modal").modal("show")
          },
          error: function(xhr, status, error){
            console.log(xhr)
          }
        })
      })

      $(document).on("click", "#update-button", function(e){
        e.preventDefault()
        let id = $("input[name='id']").val()
        $.ajax({
          type: "PUT",
          url: "product/" + id,
          headers:{
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
          },
          data: $("#form-product").serialize(),
          success: function(response){
            let html = `
              <tr id="row-`+ response.data.id +`">
                <td>`+ response.number +`</td>
                <td>`+ response.data.name +`</td>
                <td>`+ response.data.slug +`</td>
                <td>`+ response.data.description +`</td>
                <td>`+ response.data.price +`</td>
                <td>
                  <button class="btn btn-md btn-warning" data-id="`+ response.data.id +`">Edit</button>
                  <button class="btn btn-md btn-danger" data-id="`+ response.data.id +`">Hapus</button>
                </td>
              </tr>
            `

            $("#row-"+response.data.id).replaceWith(html)

            $("input[name='name']").val(null)
            $("input[name='slug']").val(null)
            $("textarea[name='description']").val(null)
            $("input[name='price']").val(null)

            $(".modal").modal("hide")
          },
          error: function(xhr, status, error){
            console.log(xhr.responseJSON)

            if(xhr.responseJSON.name){
              $("#alert-name").removeClass("d-none")
              $("#alert-name").text(xhr.responseJSON.name[0])
            }

            if(xhr.responseJSON.slug){
              $("#alert-slug").removeClass("d-none")
              $("#alert-slug").text(xhr.responseJSON.slug[0])
            }

            if(xhr.responseJSON.description){
              $("#alert-description").removeClass("d-none")
              $("#alert-description").text(xhr.responseJSON.description[0])
            }

            if(xhr.responseJSON.price){
              $("#alert-price").removeClass("d-none")
              $("#alert-price").text(xhr.responseJSON.price[0])
            }
          }
        })
      })
    </script>
  </body>
</html>