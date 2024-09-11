# セキュリティ分析の注釈

## `@psalm-taint-source <taint-type>`

[Custom taint sources](custom_taint_sources.md#taint-source-annotation) を参照。

## `@psalm-taint-sink <taint-type> <param-name>`

[Custom taint sinks](custom_taint_sinks.md) を参照。

## `@psalm-taint-escape <taint-type #conditional>`

[Escaping tainted output](avoiding_false_positives.md#escaping-tainted-output) を参照。

## `@psalm-taint-unescape <taint-type>`

[Unescaping statements](avoiding_false_negatives.md#unescaping-statements) を参照。

##`@psalm-taint-specialize`

[Specializing taints in functions](avoiding_false_positives.md#specializing-taints-in-functions) および[Specializing taints in classes](avoiding_false_positives.md#specializing-taints-in-classes) を参照。

## `@psalm-flow [proxy <function-like>] ( <arg>, [ <arg>, ] ) [ -> return ]`

参照[Taint Flow](taint_flow.md#optimized-taint-flow)
