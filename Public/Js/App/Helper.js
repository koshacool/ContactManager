;
define(function() {
    return {
        /**
         * [getUrl description]
         * Get url withour params
         *
         * @return string
         */
        getUrl: function () {
            // return window.location.origin + window.location.pathname;//Get url without params
            return window.location.pathname;//Get url without params
        },

        /**
         * [getUrlParams description]
         * Get url standart url params
         *
         * @return string
         */
        getUrlParams: function () {
            var urlParams = {
                currentPage: document.getElementById("currentPage").value,
                mainSortColumn: document.getElementById("mainSortColumn").value,
                sortDirectionMainColumn: document.getElementById("sortDirectionMainColumn").value,
                sortDirectionSecondaryColumn: document.getElementById("sortDirectionSecondaryColumn").value
            };//Create object with attributes for url params

            //Get string with url params
            var params = '';
            for (var key in urlParams) {
                if (params == '') {
                    params = '?' + key + '=' + urlParams[key];
                } else {
                    params += '&' + key + '=' + urlParams[key];
                }
            }
            return params;
        },

        /**
         * [setLocation description]
         * Set url in browser
         *
         * @param string url
         * @return string
         */
        setLocation: function (url) {
            try {
                history.pushState(null, null, url);
                return;
            } catch (e) {
                alert('Cann\'t set url');
            }
            location.hash = '#' + url;
        },

        /**
         * [allowedUrls description]
         * Return result of existing such url in object properties
         *
         * @param  url
         * @return boolean
         */
        allowedUrls: function (url) {
            var allowUrls = {
                '/contact/select': null,
                '/contact/showlist': null
            };

            if (allowUrls.hasOwnProperty(url)) {
                return true
            }
            return false;
        },

        /**
         * [ucFirst description]
         * Make a string's first character uppercase
         * 
         * @param  {[string]} str 
         * @return {[type]}   
         */
        ucFirst: function (str) {
            if (!str) {
                return str;//If empty string - return empty string
            } 
            return str[0].toUpperCase() + str.slice(1);
        },

        /**
         * [isEmptyObject description]
         * Check whether object is empty(has any properties)
         *
         * @param  object obj
         * @return boolean
         */
        isEmptyObject: function (obj) {
            for (var i in obj) {
                if (obj.hasOwnProperty(i)) {
                    return false;
                }
            }
            return true;
        },
    }
});

