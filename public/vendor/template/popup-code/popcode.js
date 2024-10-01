// Popup Code-Text
( function ( $ ) {

    var PopupCode = {};
    $body = $( document.body );

    PopupCode.init = function () {

        $body.find( '.code-content' ).each( function () {
            $this = $( this );
            var tempCode = this.outerHTML,
                deleteEnd = tempCode.lastIndexOf( "<" ),
                deleteFirst = tempCode.slice( 0, tempCode.length - 1 ).lastIndexOf( ">" ),
                strTemp = new RegExp( String( ' ' ).repeat( deleteEnd - deleteFirst - 2 ), 'gi' ),
                code = tempCode.replace( strTemp, '' );
            code = code.replace( /&quot;/gi, "'" );
            code = code.replace( / code-content/, "" );

            //if codeStyle is here
            var $codeTag = $this.closest( '.code-template' ).find( '.code-style' ),
                codeStyle = $codeTag.text().trim();
            if ( codeStyle != '' ) {
                var deleteEnd = codeStyle.lastIndexOf( "}" ),
                    deleteFirstTag = codeStyle.slice( 0, deleteEnd ).lastIndexOf( "}" ),
                    deleteFirstCharacter = codeStyle.slice( 0, deleteEnd ).lastIndexOf( ";" );
                if ( deleteFirstTag < deleteFirstCharacter ) {
                    strTemp = new RegExp( String( ' ' ).repeat( deleteEnd - deleteFirstCharacter - 2 ), 'gi' );
                } else {
                    strTemp = new RegExp( String( ' ' ).repeat( deleteEnd - deleteFirstTag - 2 ), 'gi' );
                }
                code = '<style>' + '\n' + codeStyle.replace( strTemp, '' ) + '\n' + '</style>' + '\n' + '\n' + code;
            }
            $this.attr( 'data-html-code', code );
        } );
        $body.on( 'click', '.show-code', PopupCode.event );
    };

    PopupCode.event = function ( e ) {
        e.preventDefault();
        var $this = $( this ),
            $parent = $this.closest( '.code-template' ),
            codeContent = $parent.find( '.code-content' );

        $( '.code-popup #textareaCode' ).text( codeContent.attr( 'data-html-code' ) );
        Riode.popup( {
            items: {
                src: '#code-popup'
            },
            type: 'inline',
            tLoading: '',
            mainClass: 'code-console',
        } );
        PopupCode.filterMirror();
        $( '.copy-icon' ).on( 'click', function ( e ) {
            e.preventDefault();
            $( '.code-text' ).trigger( 'select' );
            document.execCommand( 'copy' );
            $( '.code-popup .tooltiptext' ).text( 'Copied' );
        } );
    };

    PopupCode.filterMirror = function () {
        $( '.CodeMirror' ).remove();
        CodeMirror.fromTextArea( document.getElementById( 'textareaCode' ),
            {
                mode: 'text/html',
                htmlMode: true,
                lineWrapping: false,
                smartIndent: false,
                spellcheck: true,
                addModeClass: true,
                readOnly: true
            } );
        $( '.code-popup .tooltiptext' ).text( 'Copy to Clipboard' );
    };
    // Initialize Popup
    $( window ).on( 'load', function () {
        PopupCode.init();
    } );
} )( jQuery );