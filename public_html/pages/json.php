<form action='pages/json.post.php' method='post'>
    <div class="accordion mb-4">

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingOne">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    JSON Editor
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="accordion-body">

                    <pre class='edit-off'></pre>

                    <a href='#' class="edit edit-off btn btn-primary btn-sm">Editar código JSON</a>

                    <textarea id='json' class="edit-on form-control" rows=30 style='white-space:nowrap;width:100%;font-weight:100'>
                        <?= $_SESSION['current_data']; ?>
                    </textarea>

                </div>
            </div>
        </div>
    </div>

    <button class="edit-on w-100 btn btn-primary btn-lg mb-4" type="submit">Salvar alterações</button>


</form>

<script>
    $(document).ready(function() {
        var ugly = document.getElementById('json').value;
        var obj = JSON.parse(ugly);
        var pretty = JSON.stringify(obj, undefined, 4);
        document.getElementById('json').value = pretty;

        $('pre').html('<code class="language-json">' + pretty + '</code>');

        $('.edit-on').hide();

        $('.edit').click(function() {
            $('.edit-off').hide();
            $('.edit-on').show();
        });
        $('pre').dblclick(function() {
            $('.edit-off').hide();
            $('.edit-on').show();
        });
    });

    /*(function(d) {

        stylizePreElements = function() {
            var preElements = document.getElementsByTagName("pre");
            for (i = 0; i < preElements.length; ++i) {
                var preElement = preElements[i];
                preElement.className += "prettyprint";
            }
        };

        injectPrettifyScript = function() {
            var scriptElement = document.createElement('script');
            scriptElement.setAttribute("src", "https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js");
            document.head.appendChild(scriptElement);
        };

        stylizePreElements();
        injectPrettifyScript();

    })(document)*/
</script>