/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * Interaction for the Team module
 *
 * @author Frederik Heyninck <frederik@figure8.be>
 */
jsBackend.Team =
{
    // constructor
    init: function()
    {
        $('.dropdown a').click(function(e){
            $(this).parent().parent().parent().addClass('open');
        });

    	$saveAsDraft = $('#saveAsDraft');
    	$saveAsDraft.on('click', function(e)
		{
			$('form').append('<input type="hidden" name="status" value="draft" />').submit();
		});

        $('.js-seo-lang').each(function(index, el){

            var language = $(el).data('language');
            var $pageTitle = $('#seoTitle' + language);
            var $pageTitleOverwrite = $('#seoTitleOverwrite' + language);

            var $metaDescriptionOverwrite = $('#seoDescriptionOverwrite' + language);
            var $metaDescription= $('#seoDescription' + language);

            var $urlOverwrite = $('#seoUrlOverwrite' + language);
            var $url = $('#url' + language);

            var $generatedUrl = $('#generatedUrl' + language);

            var $element = $('#firstName');
            var $element2 = $('#lastName');

            $element.bind('keyup', calculateMeta);
            $element2.bind('keyup', calculateMeta);


            // generate url
           function generateUrl(url)
           {
               // make the call
               $.ajax(
               {
                   data:
                   {
                       fork: { module: 'Team', action: 'GenerateUrl' },
                       url: url,
                       className: 'Backend\\Modules\\Team\\Engine\\Model',
                       methodName: 'getUrl',
                       language: language,
                       id: $('#id').length > 0 ? $('#id').val() : null
                   },
                   success: function(data, textStatus)
                   {
                       url = data.data;
                       $url.val(url);
                       $generatedUrl.html(url);
                   },
                   error: function(XMLHttpRequest, textStatus, errorThrown)
                   {
                       url = utils.string.urlDecode(utils.string.urlise(url));
                       $url.val(url);
                       $generatedUrl.html(url);
                   }
               });
           }

            // calculate meta
           function calculateMeta(e, element)
           {
               var title = $element.val() + ' ' + $element2.val();

               if(!$pageTitleOverwrite.is(':checked')) $pageTitle.val(title);

               if(!$metaDescriptionOverwrite.is(':checked')) $metaDescription.val(title);

               if(!$urlOverwrite.is(':checked'))
               {
                   generateUrl(title);
               }
           }


            // bind change on the checkboxes
            if($pageTitle.length > 0 && $pageTitleOverwrite.length > 0)
            {
                $pageTitleOverwrite.change(function(e)
                {
                    if(!$pageTitleOverwrite.is(':checked')) $pageTitle.val($element.val() + ' ' + $element2.val());
                });
            }

            $metaDescriptionOverwrite.change(function(e)
           {
               if(!$metaDescriptionOverwrite.is(':checked')) $metaDescription.val($element.val()  + ' ' + $element2.val());
           });

           $urlOverwrite.change(function(e)
            {
                if(!urlOverwrite.is(':checked')) generateUrl($element.val() + ' ' + $element2.val());
            });

        })
    }
}

$(jsBackend.Team.init);
