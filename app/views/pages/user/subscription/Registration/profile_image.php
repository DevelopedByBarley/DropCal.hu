<div class="row  rounded mt-2  user-subscription-tab d-flex align-items-center justify-content-center">
    <div class="col">
        <form method="POST" action="/user/registration" enctype="multipart/form-data">
            <div class="user-subscription-content row">

                <div class="col-12">
                    <div class="row">
                        <div class="col-12 text-center d-flex align-items-center justify-content-center">
                            <img id="preview" src="" alt="Preview Image" style="width: 200px; height: 200px; display: none; border-radius: 50%">
                        </div>
                        <div class="col-12 mt-3 mb-5">
                            <h1 class="text-center display-4 mb-2">Töltsd fel a profil képedet!</h1>
                            <p class="text-center mb-5">Tedd még személyesebbé a profilodat!</p>
                            <div class="form-group text-center w-100">
                                <label for="fileInput" class="custom-file-upload">
                                    <img src="/public/assets/icons/photo.gif" style="height: 150px; width: 150px;" />
                                    <h3 class="display-6">
                                        Profilkép feltöltése
                                    </h3>
                                </label>
                                <input type="file" class="custom-file-input" id="fileInput" name="file">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row  mt-5">

                <div class="col-xs-12 user-navigation-buttons d-flex">
                    <a class="btn btn-outline-dark m-2 p-3" href="/user/registration/<?= (int)substr($_SERVER["REQUEST_URI"], -1) - 1 ?>">Vissza</a>
                    <button type="submit" class="btn btn-outline-dark m-2 p-3">Tovább</button>

                </div>
            </div>
        </form>
    </div>
</div>