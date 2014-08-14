Feature:

  Background:
    Given I have the following nodes:
      | Identifier                           | Path        | Node Type              | Properties        | Workspace |
      | ecf40ad1-3119-0a43-d02e-55f8b5aa3c70 | /sites      | unstructured           |                   | live      |
      | fd5ba6e1-4313-b145-1004-dad2f1173a35 | /sites/ease | SimplyAdmire.Ease:Wall | {"title": "Home"} | live      |

  @fixtures
  Scenario:
    Given I accept "application/json"
    And I send a "GET" request to "/ease/nodes"
    Then the response status code should be 200
