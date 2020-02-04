<div class="content-page" style="margin-top: 35px">
    <div class="container">
        <?php $msg->display(); ?>
        <div class="card">
            <div class="row page-title" style="padding-left: 15px">
                <div class="col-sm-4 col-xl-6">
                    <h4 class="mb-1 mt-0">People Also Ask (PAA) en Français</a></h4>
                </div>
            </div>
            <div class="row" style="padding-left: 15px">
                <div class="col-sm-10 col-xl-11">
                    <form action="#" method="POST">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <label for="Inputkeyword">Mot clé ou terme</label>
                            <input type="keyword" class="form-control" id="Inputkeyword" name="Inputkeyword"
                                   autocomplete="on" placeholder="Exemple : Qu'est-ce que qwant"
                                   value="<?= htmlspecialchars($keyword) ?? '' ?>">
                            <small class="form-text text-muted">Entrez un mot ou un terme pour obtenir une liste de
                                questions à exploiter.</small>
                        </div>
                            <div class="form-group col-md-6">
                            <label for="lang">Langue</label>
                                <select class="form-control" id="lang" name="lang">
                                    <?php HelperHTML::selected('fr', $lang) ?>
                                    <?php HelperHTML::selected('en', $lang) ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Profondeur PAA</label>
                            <select class="form-control" id="exampleFormControlSelect1" name="depth">
                                <option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="max">Max</option>
                            </select>
                        </div>
                        <input type="hidden" name="_token" value="<?= $token ?>">
                        <button type="submit" id="set_click" class="btn btn-info mb-3">Valider</button>
                    </form>
                </div>
            </div>
        </div>
        <div id="loading" style="display: none">
            <div class="card">
                <div class="row page-title" style="padding-left: 15px; text-align: center">
                    <div class="col-sm-10 col-xl-12">
                        <img src="assets/img/loader.gif" width="85" height="85">
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (ParamsRequest::methodIsset($_POST, 'Inputkeyword') &&
            ParamsRequest::methodEmpty($_POST, 'Inputkeyword', true) &&
            ParamsRequest::methodIsset($_POST, 'depth')): ?>
            <?php if (Paa::inArrayDepth()): ?>
                <div class="card">
                    <div class="row page-title" style="padding-left: 15px">
                        <div class="col-sm-4 col-xl-6">
                            <h4><?= $keyword ?> <a target="_BLANK"
                                                   href="?export=csv&keyword=<?= $slug_csv ?>&depth=<?= $depth ?>&lang=<?= $lang ?>&_token=<?= $token ?>"
                                                   class="btn btn-success btn-sm">Export CSV</a></h4>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Questions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php HelperHTML::tdRecords($paa_record) ?>
                        </tbody>
                    </table>
                </div>
            <?php endif ?>
        <?php endif ?>
        <script src="assets/js/footer.js"></script>
