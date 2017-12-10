
function parseUrl()
{
    function is(type, obj)
    {
        return Object.prototype.toString.call(obj) === '[object ' + type + ']';
    }

    function isArray(obj)
    {
        return is("Array", obj);
    }

    function isObject(obj)
    {
        return is("Object", obj);
    }

    function isString(obj)
    {
        return is("String", obj);
    }

    function isNumber(obj)
    {
        return is("Number", obj);
    }

    function isBoolean(obj)
    {
        return is("Boolean", obj);
    }

    function isNull(obj)
    {
        return typeof obj === "object" && !obj;
    }

    function isUndefined(obj)
    {
        return typeof obj === "undefined";
    }

    function querystring_parse(qs, sep, eq, unesc)
    {
        return qs.split(sep || "&")
            .map(pieceParser(eq || "=", unesc || unescape))
            .reduce(mergeParams, {});
    };

    function unescape(s)
    {
        return decodeURIComponent(s.replace(/\+/g, ' '));
    };

    // Parse a key=val string.
    // These can get pretty hairy
    // example flow:
    // parse(foo[bar][][bla]=baz)
    // return parse(foo[bar][][bla],"baz")
    // return parse(foo[bar][], {bla : "baz"})
    // return parse(foo[bar], [{bla:"baz"}])
    // return parse(foo, {bar:[{bla:"baz"}]})
    // return {foo:{bar:[{bla:"baz"}]}}
    function pieceParser(eq, unesc)
    {
        return function parsePiece(key, val) {
            if (arguments.length !== 2) {
                // key=val, called from the map/reduce
                key = key.split(eq);
                return parsePiece(
                    unesc(key.shift()),
                    unesc(key.join(eq))
                );
            }
            key = key.replace(/^\s+|\s+$/g, '');
            if (isString(val)) {
                val = val.replace(/^\s+|\s+$/g, '');
                // convert numerals to numbers
                if (!isNaN(val)) {
                    var numVal = +val;
                    if (val === numVal.toString(10)) val = numVal;
                }
            }
            var sliced = /(.*)\[([^\]]*)\]$/.exec(key);
            if (!sliced) {
                var ret = {};
                if (key) ret[key] = val;
                return ret;
            }
            // ["foo[][bar][][baz]", "foo[][bar][]", "baz"]
            var tail = sliced[2],
                head = sliced[1];

            // array: key[]=val
            if (!tail) return parsePiece(head, [val]);

            // obj: key[subkey]=val
            var ret = {};
            ret[tail] = val;
            return parsePiece(head, ret);
        };
    };

// the reducer function that merges each query piece together into one set of params
    function mergeParams(params, addition)
    {
        var ret;

        if (!params) {
            // if it's uncontested, then just return the addition.
            ret = addition;
        } else if (isArray(params)) {
            // if the existing value is an array, then concat it.
            ret = params.concat(addition);
        } else if (!isObject(params) || !isObject(addition)) {
            // if the existing value is not an array, and either are not objects, arrayify it.
            ret = [params].concat(addition);
        } else {
            // else merge them as objects, which is a little more complex
            ret = mergeObjects(params, addition);
        }
        return ret;
    };


    // Merge two *objects* together. If this is called, we've already ruled
    // out the simple cases, and need to do the for-in business.
    function mergeObjects(params, addition)
    {
        for (var i in addition) if (i && addition.hasOwnProperty(i))
        {
            params[i] = mergeParams(params[i], addition[i]);
        }

        return params;
    };

    return {
        parse: querystring_parse,
        unescape: unescape
    };
}
