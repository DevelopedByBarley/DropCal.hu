<div class="cookie-container">
    <div class="cookie-modal text-center <?php echo isset($_COOKIE["cookie_accepted"]) ? '' : 'active' ?>" id="cookie-modal-banner">
        <div class="cookie-modal-content bg-light">
            <p><b class="text-info">Sütiket használunk a jobb felhasználói élmény érdekében.</b> Hogy megfeleljünk az
                új elektronikus hírközlési adatvédelmi irányelvnek, engedélyt kell
                kérnünk a sütik használatához.

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cookieconsent3">
                    Cookie beállítások
                </button>
                <button id="accept_cookie" class="btn btn-info text-light">Megértettem</button>
        </div>
    </div>

    <!-- Button trigger modal -->

    <!-- Modal -->
    <form onsubmit="setCookieSettings(event)">
        <div class="modal top fade" id="cookieconsent3" tabindex="-1" aria-labelledby="cookieconsentLabel3" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content d-block text-start">
                    <div class="modal-header d-block ">
                        <h5 class="modal-title" id="cookieconsentLabel3">Cookies & Privacy</h5>
                        <p>
                            This website uses cookies to ensure you get the best experience on our website.
                        </p>
                        <p>
                            <a href="#">További információk.</a>
                        </p>
                    </div>
                    <div class="modal-body">
                        <!-- Necessary checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="necessary" name="necessary_cookies" checked disabled />
                            <label class="form-check-label" for="necessary">
                                <p>
                                    <strong>Necessary cookies</strong>
                                    <muted>help with the basic functionality of our website, e.g remember if you gave consent to cookies.</muted>
                                </p>
                            </label>
                        </div>
                        <!-- Analytical checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="analytical" name="analytical_cookies" />
                            <label class="form-check-label" for="analytical">
                                <p>
                                    <strong>Analytical cookies</strong>
                                    <muted>make it possible to gather statistics about the use and trafiic on our website, so we can make it better.</muted>
                                </p>
                            </label>
                        </div>
                        <!-- Marketing checkbox -->
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="marketing" name="marketing_cookies" />
                            <label class="form-check-label" for="marketing">
                                <p>
                                    <strong>Marketing cookies</strong>
                                    <muted>make it possible to show you more relevant social media content and advertisements on our website and other platforms.</muted>
                                </p>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary" data-bs-dismiss="modal" id="cookie_modal_settings">
                            Beállítások mentése
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>