var BootstrapSelect = function () {
    
        //== Private functions
        var demos = function () {
            // minimum setup
            $('.m_selectpicker').selectpicker();
            $('.m_selectpicker1').selectpicker();
            $('.custom_select').selectpicker();
        }

        return {
            // public functions
            init: function() {
                demos(); 
            }
        };
    }();

    jQuery(document).ready(function() {    
        BootstrapSelect.init();
    });




